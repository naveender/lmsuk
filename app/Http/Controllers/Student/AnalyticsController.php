<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\PaperAttempt;
use App\Models\StudentAnswer;
use App\Models\StudentVideoProgress;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AnalyticsController extends Controller
{
    private const STRONG_THRESHOLD = 80;
    private const DEVELOPING_THRESHOLD = 61;

    /**
     * Display the Student Analytics Dashboard using real student performance data.
     */
    public function index(Request $request)
    {
        $student = auth()->user();
        if (!$student) {
            return redirect()->route('login');
        }

        $period = $request->input('period', '12_weeks');
        if (!in_array($period, ['4_weeks', '8_weeks', '12_weeks', 'all'], true)) {
            $period = '12_weeks';
        }

        $periodLabels = [
            '4_weeks'  => 'Last 4 Weeks',
            '8_weeks'  => 'Last 8 Weeks',
            '12_weeks' => 'Last 12 Weeks',
            'all'      => 'All Time',
        ];

        $windowWeeks = match ($period) {
            '4_weeks'  => 4,
            '8_weeks'  => 8,
            '12_weeks' => 12,
            default    => null,
        };

        $startDate = $windowWeeks ? now()->subWeeks($windowWeeks)->startOfDay() : null;

        $allAttempts = PaperAttempt::with(['paper.subject', 'paper.topic', 'paper.subtopic.parentTopic'])
            ->where('user_id', $student->id)
            ->where('status', 'completed')
            ->orderByRaw('COALESCE(completed_at, created_at) DESC')
            ->get();

        $periodAttempts = $startDate
            ? $allAttempts->filter(function ($attempt) use ($startDate) {
                $dt = $attempt->completed_at ?? $attempt->created_at;
                return $dt && $dt->greaterThanOrEqualTo($startDate);
            })->values()
            : $allAttempts;

        // If default load has no attempts in 12 weeks but older attempts exist, switch cleanly to All Time
        if (!$request->has('period') && $periodAttempts->isEmpty() && $allAttempts->isNotEmpty()) {
            $period = 'all';
            $startDate = null;
            $windowWeeks = null;
            $activeAttempts = $allAttempts;
        } else {
            $activeAttempts = $periodAttempts;
        }

        $selectedPeriodLabel = $periodLabels[$period];
        $allTimeAssessments = $allAttempts->count();

        // Overall KPI summary metrics
        $summary = $this->computeSummaryMetrics($activeAttempts, $allAttempts, $startDate, $windowWeeks);
        $totalAssessments = $summary['total_assessments'];
        $averageScore = $summary['average_score'];
        $bestScore = $summary['best_score'];
        $latestScore = $summary['latest_score'];
        $scoreGrowth = $summary['score_growth'];
        $overallProgress = $summary['overall_progress'];

        // Real Learning Streak (in days)
        $streak = $this->calculateStreak($student->id, $allAttempts);

        // Scored attempts in active scope
        $scoredAttempts = $activeAttempts->filter(fn($a) => $a->max_score > 0)->values();

        // Performance Trend (chronological real attempts or weekly averages)
        $trendData = $this->getPerformanceTrend($scoredAttempts);

        // Subject Performance (real active subjects & real student averages)
        $subjectPerformance = $this->getSubjectPerformance($scoredAttempts);
        $hasSubjectData = $scoredAttempts->isNotEmpty();

        // Recent Assessments Table (top 6 for initial SSR; extended payload up to 30 for instant subject/search filtering)
        $formattedAttempts = $this->formatAttemptsCollection($activeAttempts->take(30));
        $recentAssessments = $formattedAttempts->take(6)->values();
        $recentAssessmentsPayload = $formattedAttempts->map(function ($item) {
            return [
                'id'                 => $item->id,
                'title'              => $item->paper?->title ?? 'Assessment',
                'topic_name'         => $item->topic_name,
                'subject_key'        => $item->subject_badge_type,
                'subject_badge'      => $item->subject_badge_name,
                'score_pct'          => $item->score_pct,
                'score_fraction'     => $item->score_fraction,
                'status_tier'        => $item->status_tier,
                'date_display'       => $item->display_date ? $item->display_date->format('d M Y') : '',
                'url'                => route('student.attempts.result', $item->id),
            ];
        })->values()->all();

        // Accuracy by Topic (top 8 for initial SSR + full list for filtering & insight banner)
        $allTopicAccuracies = $this->getTopicAccuracies($scoredAttempts, null);
        $accuracyByTopic = array_slice($allTopicAccuracies, 0, 8);

        $lowestTopic = collect($allTopicAccuracies)
            ->sortBy('accuracy')
            ->first(fn($t) => $t['accuracy'] < self::STRONG_THRESHOLD);

        // Per-subject options & precomputed real analytics map for instant toolbar pill filtering
        $subjectOptions = collect($subjectPerformance)
            ->map(fn($s) => [
                'key'   => $s['key'],
                'title' => $s['title'],
                'color' => $s['color'],
                'tests' => $s['tests'],
            ])
            ->values()
            ->all();

        $subjectAnalyticsMap = $this->buildSubjectAnalyticsMap(
            $subjectOptions,
            $activeAttempts,
            $allAttempts,
            $startDate,
            $windowWeeks,
            $summary,
            $trendData,
            $allTopicAccuracies
        );

        $requestedSubject = strtolower(trim((string) $request->input('subject', 'all')));
        $validSubjectKeys = collect($subjectOptions)->pluck('key')->all();
        $activeSubjectFilter = in_array($requestedSubject, $validSubjectKeys, true) ? $requestedSubject : 'all';

        return view('student.analytics.student-analytics', compact(
            'period',
            'periodLabels',
            'selectedPeriodLabel',
            'allTimeAssessments',
            'totalAssessments',
            'averageScore',
            'bestScore',
            'latestScore',
            'scoreGrowth',
            'overallProgress',
            'streak',
            'trendData',
            'subjectPerformance',
            'hasSubjectData',
            'recentAssessments',
            'recentAssessmentsPayload',
            'accuracyByTopic',
            'allTopicAccuracies',
            'lowestTopic',
            'subjectOptions',
            'subjectAnalyticsMap',
            'activeSubjectFilter'
        ));
    }

    /**
     * Compute real KPI metrics for a scope of attempts (overall or filtered by subject).
     */
    protected function computeSummaryMetrics(
        Collection $scopeAttempts,
        Collection $allAttempts,
        ?Carbon $startDate,
        ?int $windowWeeks,
        ?string $subjectKey = null
    ): array {
        $totalAssessments = $scopeAttempts->count();
        $scoredAttempts = $scopeAttempts->filter(fn($a) => $a->max_score > 0)->values();

        $averageScore = $scoredAttempts->isNotEmpty()
            ? (int) round($scoredAttempts->avg(fn($a) => ($a->score / $a->max_score) * 100))
            : 0;

        $bestScore = $scoredAttempts->isNotEmpty()
            ? (int) round($scoredAttempts->max(fn($a) => ($a->score / $a->max_score) * 100))
            : null;

        $latestAttempt = $scoredAttempts->first();
        $latestScore = $latestAttempt
            ? (int) round(($latestAttempt->score / $latestAttempt->max_score) * 100)
            : null;

        // Score growth: recent half vs older half of scored attempts in scope
        $scoreGrowth = null;
        if ($scoredAttempts->count() >= 2) {
            $splitAt = (int) ceil($scoredAttempts->count() / 2);
            $recentHalf = $scoredAttempts->take($splitAt);
            $olderHalf = $scoredAttempts->slice($splitAt);

            if ($recentHalf->isNotEmpty() && $olderHalf->isNotEmpty()) {
                $recentAvg = $recentHalf->avg(fn($a) => ($a->score / $a->max_score) * 100);
                $olderAvg = $olderHalf->avg(fn($a) => ($a->score / $a->max_score) * 100);
                $scoreGrowth = (int) round($recentAvg - $olderAvg);
            }
        }

        // Overall progress vs immediately preceding time window
        $overallProgress = null;
        if ($scoredAttempts->isNotEmpty() && $startDate && $windowWeeks) {
            $prevStartDate = (clone $startDate)->subWeeks($windowWeeks);
            $prevScored = $allAttempts->filter(function ($attempt) use ($prevStartDate, $startDate, $subjectKey) {
                if ($attempt->max_score <= 0) {
                    return false;
                }
                if ($subjectKey !== null) {
                    $meta = $this->resolveSubjectMeta($attempt->paper?->subject?->title ?? '');
                    if ($meta['key'] !== $subjectKey) {
                        return false;
                    }
                }
                $dt = $attempt->completed_at ?? $attempt->created_at;
                return $dt && $dt->greaterThanOrEqualTo($prevStartDate) && $dt->lt($startDate);
            });

            if ($prevScored->isNotEmpty()) {
                $prevAvg = $prevScored->avg(fn($a) => ($a->score / $a->max_score) * 100);
                $overallProgress = (int) round($averageScore - $prevAvg);
            }
        }

        return [
            'total_assessments' => $totalAssessments,
            'average_score'     => $averageScore,
            'best_score'        => $bestScore,
            'latest_score'      => $latestScore,
            'score_growth'      => $scoreGrowth,
            'overall_progress'  => $overallProgress,
        ];
    }

    /**
     * Build per-subject analytics snapshots so toolbar subject pills update KPIs and charts seamlessly.
     */
    protected function buildSubjectAnalyticsMap(
        array $subjectOptions,
        Collection $activeAttempts,
        Collection $allAttempts,
        ?Carbon $startDate,
        ?int $windowWeeks,
        array $overallSummary,
        array $overallTrend,
        array $allTopicAccuracies
    ): array {
        $map = [
            'all' => [
                'summary' => $overallSummary,
                'trend'   => $overallTrend,
                'topics'  => array_slice($allTopicAccuracies, 0, 8),
            ],
        ];

        foreach ($subjectOptions as $opt) {
            $key = $opt['key'];
            $subjActive = $activeAttempts->filter(function ($attempt) use ($key) {
                $meta = $this->resolveSubjectMeta($attempt->paper?->subject?->title ?? '');
                return $meta['key'] === $key;
            })->values();

            $subjScored = $subjActive->filter(fn($a) => $a->max_score > 0)->values();
            $subjSummary = $this->computeSummaryMetrics($subjActive, $allAttempts, $startDate, $windowWeeks, $key);
            $subjTrend = $this->getPerformanceTrend($subjScored);
            $subjTopics = collect($allTopicAccuracies)->where('subject', $key)->values()->take(8)->all();

            $map[$key] = [
                'summary' => $subjSummary,
                'trend'   => $subjTrend,
                'topics'  => $subjTopics,
            ];
        }

        return $map;
    }

    /**
     * Attach normalized display metadata to PaperAttempt models.
     */
    protected function formatAttemptsCollection(Collection $attempts): Collection
    {
        return $attempts->map(function ($item) {
            $pct = $item->max_score > 0 ? (int) round(($item->score / $item->max_score) * 100) : 0;
            $item->score_pct = $pct;
            $item->score_fraction = $item->max_score > 0
                ? ((float) $item->score == (int) $item->score ? (int) $item->score : $item->score)
                    . '/'
                    . ((float) $item->max_score == (int) $item->max_score ? (int) $item->max_score : $item->max_score)
                : null;
            $item->display_date = $item->completed_at ?? $item->created_at;

            $resolvedTopic = $item->paper?->topic ?: ($item->paper?->subtopic?->parentTopic ?: $item->paper?->subtopic);
            $item->topic_name = $resolvedTopic?->name;

            $subTitle = $item->paper?->subject?->title ?? 'General';
            $meta = $this->resolveSubjectMeta($subTitle);
            $item->subject_badge_type = $meta['key'];
            $item->subject_badge_name = $meta['badge'];

            $item->status_tier = $pct >= self::STRONG_THRESHOLD
                ? 'strong'
                : ($pct >= self::DEVELOPING_THRESHOLD ? 'developing' : 'focus');

            return $item;
        });
    }

    /**
     * Calculate continuous active learning streak in days ending today or yesterday.
     */
    protected function calculateStreak(int $userId, Collection $allAttempts): int
    {
        $attemptDates = $allAttempts
            ->map(fn($a) => ($a->completed_at ?? $a->created_at)?->toDateString())
            ->filter();

        $videoDates = StudentVideoProgress::where('user_id', $userId)
            ->pluck('updated_at')
            ->map(fn($dt) => $dt?->toDateString())
            ->filter();

        $dates = $attemptDates->merge($videoDates)->unique()->values();
        if ($dates->isEmpty()) {
            return 0;
        }

        $today = Carbon::today()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();

        $current = $dates->contains($today)
            ? Carbon::today()
            : ($dates->contains($yesterday) ? Carbon::yesterday() : null);

        if (!$current) {
            return 0;
        }

        $streak = 0;
        while ($dates->contains($current->toDateString())) {
            $streak++;
            $current->subDay();
        }

        return $streak;
    }

    /**
     * Build chronological performance trend data strictly from real scored attempts.
     */
    protected function getPerformanceTrend(Collection $scoredAttempts): array
    {
        if ($scoredAttempts->isEmpty()) {
            return [
                'categories' => [],
                'series'     => [],
                'labels'     => [],
            ];
        }

        $chronological = $scoredAttempts
            ->sortBy(fn($a) => ($a->completed_at ?? $a->created_at)?->timestamp ?? 0)
            ->values();

        if ($chronological->count() <= 12) {
            $categories = [];
            $series = [];
            $labels = [];

            foreach ($chronological as $attempt) {
                $dt = $attempt->completed_at ?? $attempt->created_at;
                $categories[] = $dt ? $dt->format('d M') : 'Test';
                $series[] = (int) round(($attempt->score / $attempt->max_score) * 100);
                $labels[] = $attempt->paper?->title ?? 'Assessment';
            }

            return [
                'categories' => $categories,
                'series'     => $series,
                'labels'     => $labels,
            ];
        }

        // Aggregate by week when more than 12 attempts exist
        $byWeek = $chronological->groupBy(function ($attempt) {
            $dt = $attempt->completed_at ?? $attempt->created_at ?? now();
            return $dt->copy()->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
        });

        $categories = [];
        $series = [];
        $labels = [];

        foreach ($byWeek as $weekStart => $attemptsInWeek) {
            $avg = (int) round($attemptsInWeek->avg(fn($a) => ($a->score / $a->max_score) * 100));
            $categories[] = Carbon::parse($weekStart)->format('d M');
            $series[] = $avg;
            $labels[] = $attemptsInWeek->count() . ' ' . Str::plural('test', $attemptsInWeek->count());
        }

        return [
            'categories' => $categories,
            'series'     => $series,
            'labels'     => $labels,
        ];
    }

    /**
     * Calculate subject performance breakdown from real active subjects & attempts.
     */
    protected function getSubjectPerformance(Collection $scoredAttempts): array
    {
        $subjects = Subject::where('is_active', true)->orderBy('id')->get();
        $breakdown = [];

        foreach ($subjects as $subject) {
            $subjAttempts = $scoredAttempts->filter(fn($a) => (int) ($a->paper?->subject_id ?? 0) === (int) $subject->id);
            $meta = $this->resolveSubjectMeta($subject->title);

            $breakdown[] = [
                'id'          => $subject->id,
                'key'         => $meta['key'],
                'title'       => $meta['title'],
                'score'       => $subjAttempts->isNotEmpty()
                    ? (int) round($subjAttempts->avg(fn($a) => ($a->score / $a->max_score) * 100))
                    : 0,
                'color'       => $meta['color'],
                'color_light' => $meta['color_light'],
                'icon'        => $meta['icon'],
                'tests'       => $subjAttempts->count(),
            ];
        }

        // Include any subjects from attempts whose subject may no longer be marked active
        $knownIds = $subjects->pluck('id')->all();
        $extraAttempts = $scoredAttempts->filter(fn($a) => $a->paper?->subject && !in_array($a->paper->subject_id, $knownIds, true));

        foreach ($extraAttempts->groupBy(fn($a) => $a->paper->subject_id) as $subjectId => $group) {
            $subject = $group->first()->paper->subject;
            $meta = $this->resolveSubjectMeta($subject->title);

            $breakdown[] = [
                'id'          => $subjectId,
                'key'         => $meta['key'],
                'title'       => $meta['title'],
                'score'       => (int) round($group->avg(fn($a) => ($a->score / $a->max_score) * 100)),
                'color'       => $meta['color'],
                'color_light' => $meta['color_light'],
                'icon'        => $meta['icon'],
                'tests'       => $group->count(),
            ];
        }

        return $breakdown;
    }

    /**
     * Calculate topic accuracies strictly from the student's real completed attempts.
     */
    protected function getTopicAccuracies(Collection $scoredAttempts, ?int $limit = 8): array
    {
        if ($scoredAttempts->isEmpty()) {
            return [];
        }

        $topicBuckets = [];
        $untaggedAttemptIds = [];

        foreach ($scoredAttempts as $attempt) {
            $paper = $attempt->paper;
            if (!$paper) {
                continue;
            }

            $resolvedTopic = $paper->topic ?: ($paper->subtopic?->parentTopic ?: $paper->subtopic);
            if (!$resolvedTopic) {
                $untaggedAttemptIds[] = $attempt->id;
                continue;
            }

            $key = 'topic_' . $resolvedTopic->id;
            if (!isset($topicBuckets[$key])) {
                $topicBuckets[$key] = [
                    'id'          => $resolvedTopic->id,
                    'name'        => $resolvedTopic->name,
                    'subject_obj' => $paper->subject ?: $resolvedTopic->subject,
                    'scores'      => [],
                ];
            }
            $topicBuckets[$key]['scores'][] = round(($attempt->score / $attempt->max_score) * 100);
        }

        if (!empty($untaggedAttemptIds)) {
            $answers = StudentAnswer::with(['question.topic', 'question.subtopic.parentTopic', 'question.subject'])
                ->whereIn('paper_attempt_id', $untaggedAttemptIds)
                ->get();

            $answerBuckets = [];
            foreach ($answers as $answer) {
                $question = $answer->question;
                if (!$question) {
                    continue;
                }

                $resolvedTopic = $question->topic ?: ($question->subtopic?->parentTopic ?: $question->subtopic);
                if (!$resolvedTopic) {
                    continue;
                }

                $key = 'topic_' . $resolvedTopic->id;
                if (isset($topicBuckets[$key])) {
                    continue;
                }

                if (!isset($answerBuckets[$key])) {
                    $answerBuckets[$key] = [
                        'id'          => $resolvedTopic->id,
                        'name'        => $resolvedTopic->name,
                        'subject_obj' => $question->subject ?: $resolvedTopic->subject,
                        'earned'      => 0.0,
                        'possible'    => 0.0,
                    ];
                }

                $maxMarks = (float) ($question->marks > 0 ? $question->marks : 1);
                $earned = $answer->marks_obtained !== null
                    ? (float) $answer->marks_obtained
                    : ($answer->is_correct ? $maxMarks : 0.0);

                $answerBuckets[$key]['earned'] += $earned;
                $answerBuckets[$key]['possible'] += $maxMarks;
            }

            foreach ($answerBuckets as $key => $bucket) {
                if ($bucket['possible'] > 0) {
                    $topicBuckets[$key] = [
                        'id'          => $bucket['id'],
                        'name'        => $bucket['name'],
                        'subject_obj' => $bucket['subject_obj'],
                        'scores'      => [round(($bucket['earned'] / $bucket['possible']) * 100)],
                    ];
                }
            }
        }

        $items = collect($topicBuckets)->map(function (array $bucket) {
            $meta = $this->resolveSubjectMeta($bucket['subject_obj']?->title ?? '');
            $accuracy = (int) max(0, min(100, round(collect($bucket['scores'])->avg())));
            $status = $accuracy >= self::STRONG_THRESHOLD
                ? ['key' => 'strong', 'label' => 'Strong']
                : ($accuracy >= self::DEVELOPING_THRESHOLD
                    ? ['key' => 'developing', 'label' => 'Developing']
                    : ['key' => 'focus', 'label' => 'Focus']);

            return [
                'id'            => $bucket['id'],
                'name'          => $bucket['name'],
                'subject'       => $meta['key'],
                'subject_badge' => $meta['badge'],
                'color'         => $meta['color'],
                'accuracy'      => $accuracy,
                'attempts'      => count($bucket['scores']),
                'status'        => $status['key'],
                'status_label'  => $status['label'],
                'url'           => route('student.topics.subtopics', $bucket['id']),
            ];
        })->sortByDesc('accuracy')->values();

        return $limit !== null ? $items->take($limit)->all() : $items->all();
    }

    /**
     * Normalize subject metadata for badges, chart bars, and icons.
     */
    protected function resolveSubjectMeta(string $title): array
    {
        $lower = strtolower(trim($title));

        if (str_contains($lower, 'eng')) {
            return [
                'key'         => 'english',
                'title'       => 'English',
                'badge'       => 'English',
                'color'       => '#ef4444',
                'color_light' => '#ff6b6b',
                'icon'        => 'book-open',
            ];
        }

        if (str_contains($lower, 'math')) {
            return [
                'key'         => 'maths',
                'title'       => 'Maths',
                'badge'       => 'Maths',
                'color'       => '#2563eb',
                'color_light' => '#60a5fa',
                'icon'        => 'calculator',
            ];
        }

        if (str_contains($lower, 'non') || str_contains($lower, 'nvr')) {
            return [
                'key'         => 'nvr',
                'title'       => 'Non-Verbal Reasoning',
                'badge'       => 'NVR',
                'color'       => '#8b5cf6',
                'color_light' => '#a78bfa',
                'icon'        => 'shapes',
            ];
        }

        if (str_contains($lower, 'verbal') || $lower === 'vr') {
            return [
                'key'         => 'vr',
                'title'       => 'Verbal Reasoning',
                'badge'       => 'VR',
                'color'       => '#f59e0b',
                'color_light' => '#fbbf24',
                'icon'        => 'brain',
            ];
        }

        if (str_contains($lower, 'sci')) {
            return [
                'key'         => 'science',
                'title'       => 'Science',
                'badge'       => 'Science',
                'color'       => '#10b981',
                'color_light' => '#34d399',
                'icon'        => 'zap',
            ];
        }

        $clean = $title !== '' ? $title : 'General';
        return [
            'key'         => $lower !== '' ? 'subject_' . preg_replace('/[^a-z0-9]+/', '_', $lower) : 'default',
            'title'       => $clean,
            'badge'       => $clean,
            'color'       => '#6366f1',
            'color_light' => '#818cf8',
            'icon'        => 'layers',
        ];
    }
}

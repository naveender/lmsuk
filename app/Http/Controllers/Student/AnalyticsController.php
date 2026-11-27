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

class AnalyticsController extends Controller
{
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

        // 1. Total Assessments
        $totalAssessments = $activeAttempts->count();

        // 2. Average Score (%)
        $scoredAttempts = $activeAttempts->filter(fn($a) => $a->max_score > 0)->values();
        $averageScore = $scoredAttempts->isNotEmpty()
            ? (int) round($scoredAttempts->avg(fn($a) => ($a->score / $a->max_score) * 100))
            : 0;

        // 3. Score Growth (recent half vs older half of scored attempts in period)
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

        // 4. Overall Progress vs immediately preceding window
        $overallProgress = null;
        if ($scoredAttempts->isNotEmpty() && $startDate && $windowWeeks) {
            $prevStartDate = (clone $startDate)->subWeeks($windowWeeks);
            $prevScored = $allAttempts->filter(function ($attempt) use ($prevStartDate, $startDate) {
                if ($attempt->max_score <= 0) {
                    return false;
                }
                $dt = $attempt->completed_at ?? $attempt->created_at;
                return $dt && $dt->greaterThanOrEqualTo($prevStartDate) && $dt->lt($startDate);
            });

            if ($prevScored->isNotEmpty()) {
                $prevAvg = $prevScored->avg(fn($a) => ($a->score / $a->max_score) * 100);
                $overallProgress = (int) round($averageScore - $prevAvg);
            }
        }

        // 5. Real Learning Streak (in days)
        $streak = $this->calculateStreak($student->id, $allAttempts);

        // 6. Performance Trend (chronological real attempts or weekly averages)
        $trendData = $this->getPerformanceTrend($scoredAttempts);

        // 7. Subject Performance (real active subjects & real student averages)
        $subjectPerformance = $this->getSubjectPerformance($scoredAttempts);
        $hasSubjectData = $scoredAttempts->isNotEmpty();

        // 8. Recent Assessments Table (top 6 in active scope)
        $recentAssessments = $activeAttempts->take(6)->map(function ($item) {
            $pct = $item->max_score > 0 ? (int) round(($item->score / $item->max_score) * 100) : 0;
            $item->score_pct = $pct;
            $item->display_date = $item->completed_at ?? $item->created_at;

            $subTitle = $item->paper?->subject?->title ?? 'General';
            $meta = $this->resolveSubjectMeta($subTitle);
            $item->subject_badge_type = $meta['key'];
            $item->subject_badge_name = $meta['badge'];

            return $item;
        });

        // 9. Accuracy by Topic (real completed topic scores in active period)
        $accuracyByTopic = $this->getTopicAccuracies($scoredAttempts);

        return view('student.analytics.student-analytics', compact(
            'period',
            'periodLabels',
            'selectedPeriodLabel',
            'totalAssessments',
            'averageScore',
            'scoreGrowth',
            'overallProgress',
            'streak',
            'trendData',
            'subjectPerformance',
            'hasSubjectData',
            'recentAssessments',
            'accuracyByTopic'
        ));
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
            $labels[] = $attemptsInWeek->count() . ' ' . \Illuminate\Support\Str::plural('test', $attemptsInWeek->count());
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
                'id'    => $subject->id,
                'key'   => $meta['key'],
                'title' => $meta['title'],
                'score' => $subjAttempts->isNotEmpty()
                    ? (int) round($subjAttempts->avg(fn($a) => ($a->score / $a->max_score) * 100))
                    : 0,
                'color' => $meta['color'],
                'icon'  => $meta['icon'],
                'tests' => $subjAttempts->count(),
            ];
        }

        // Include any subjects from attempts whose subject may no longer be marked active
        $knownIds = $subjects->pluck('id')->all();
        $extraAttempts = $scoredAttempts->filter(fn($a) => $a->paper?->subject && !in_array($a->paper->subject_id, $knownIds, true));

        foreach ($extraAttempts->groupBy(fn($a) => $a->paper->subject_id) as $subjectId => $group) {
            $subject = $group->first()->paper->subject;
            $meta = $this->resolveSubjectMeta($subject->title);

            $breakdown[] = [
                'id'    => $subjectId,
                'key'   => $meta['key'],
                'title' => $meta['title'],
                'score' => (int) round($group->avg(fn($a) => ($a->score / $a->max_score) * 100)),
                'color' => $meta['color'],
                'icon'  => $meta['icon'],
                'tests' => $group->count(),
            ];
        }

        return $breakdown;
    }

    /**
     * Calculate topic accuracies strictly from the student's real completed attempts.
     */
    protected function getTopicAccuracies(Collection $scoredAttempts): array
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

        return collect($topicBuckets)->map(function (array $bucket) {
            $meta = $this->resolveSubjectMeta($bucket['subject_obj']?->title ?? '');
            $accuracy = (int) max(0, min(100, round(collect($bucket['scores'])->avg())));

            return [
                'id'       => $bucket['id'],
                'name'     => $bucket['name'],
                'subject'  => $meta['key'],
                'color'    => $meta['color'],
                'accuracy' => $accuracy,
            ];
        })->sortByDesc('accuracy')->values()->take(8)->all();
    }

    /**
     * Normalize subject metadata for badges, chart bars, and icons.
     */
    protected function resolveSubjectMeta(string $title): array
    {
        $lower = strtolower(trim($title));

        if (str_contains($lower, 'eng')) {
            return [
                'key'   => 'english',
                'title' => 'English',
                'badge' => 'English',
                'color' => '#ef4444',
                'icon'  => 'book-open',
            ];
        }

        if (str_contains($lower, 'math')) {
            return [
                'key'   => 'maths',
                'title' => 'Maths',
                'badge' => 'Maths',
                'color' => '#2563eb',
                'icon'  => 'calculator',
            ];
        }

        if (str_contains($lower, 'non') || str_contains($lower, 'nvr')) {
            return [
                'key'   => 'nvr',
                'title' => 'Non-Verbal Reasoning',
                'badge' => 'NVR',
                'color' => '#8b5cf6',
                'icon'  => 'shapes',
            ];
        }

        if (str_contains($lower, 'verbal') || $lower === 'vr') {
            return [
                'key'   => 'vr',
                'title' => 'Verbal Reasoning',
                'badge' => 'VR',
                'color' => '#f59e0b',
                'icon'  => 'brain',
            ];
        }

        if (str_contains($lower, 'sci')) {
            return [
                'key'   => 'science',
                'title' => 'Science',
                'badge' => 'Science',
                'color' => '#10b981',
                'icon'  => 'zap',
            ];
        }

        $clean = $title !== '' ? $title : 'General';
        return [
            'key'   => 'default',
            'title' => $clean,
            'badge' => $clean,
            'color' => '#6366f1',
            'icon'  => 'layers',
        ];
    }
}

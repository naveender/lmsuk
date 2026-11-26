<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\PaperAttempt;
use App\Models\StudentAnswer;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class FocusAreasController extends Controller
{
    private const STRONG_THRESHOLD = 80;
    private const DEVELOPING_THRESHOLD = 61;

    /**
     * Display the Student Focus Areas dashboard.
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
        $selectedPeriodLabel = $periodLabels[$period];

        $startDate = match ($period) {
            '4_weeks'  => now()->subWeeks(4)->startOfDay(),
            '8_weeks'  => now()->subWeeks(8)->startOfDay(),
            '12_weeks' => now()->subWeeks(12)->startOfDay(),
            'all'      => null,
        };

        $allAttempts = PaperAttempt::with(['paper.subject', 'paper.topic', 'paper.subtopic.parentTopic'])
            ->where('user_id', $student->id)
            ->where('status', 'completed')
            ->where('max_score', '>', 0)
            ->orderByRaw('COALESCE(completed_at, created_at) DESC')
            ->get();

        $periodAttempts = $startDate
            ? $allAttempts->filter(function ($attempt) use ($startDate) {
                $attemptDate = $attempt->completed_at ?? $attempt->created_at;
                return $attemptDate && $attemptDate->greaterThanOrEqualTo($startDate);
            })->values()
            : $allAttempts;

        // On initial default load without explicit period param, fall back to all attempts if recent window is empty
        $activeAttempts = ($periodAttempts->isEmpty() && !$request->has('period') && $allAttempts->isNotEmpty())
            ? $allAttempts
            : $periodAttempts;

        $isSamplePreview = $allAttempts->isEmpty();

        if ($isSamplePreview) {
            $topicItems = $this->buildSampleTopicItems();
            $subjectOverview = $this->buildSampleSubjectOverview($topicItems);
        } else {
            $topicItems = $this->buildTopicItemsFromAttempts($activeAttempts);
            $subjectOverview = $this->buildSubjectOverview($activeAttempts, $topicItems);
        }

        // Default ordering: Accuracy (High to Low)
        $topicItems = $topicItems->sortByDesc('accuracy')->values();

        // KPI counts directly consistent with evaluated topics
        $strongCount = $topicItems->where('status', 'strong')->count();
        $developingCount = $topicItems->where('status', 'developing')->count();
        $focusCount = $topicItems->where('status', 'focus')->count();
        $totalTopicsCount = $topicItems->count();

        // Areas to improve card: lowest-scoring focus topics first
        $areasToImprove = $topicItems
            ->where('status', 'focus')
            ->sortBy('accuracy')
            ->values()
            ->take(3);

        // Subject options for the filter dropdown
        $subjectOptions = $this->buildSubjectFilterOptions($topicItems, $subjectOverview);

        return view('student.focusareas.index', compact(
            'period',
            'periodLabels',
            'selectedPeriodLabel',
            'isSamplePreview',
            'topicItems',
            'subjectOverview',
            'subjectOptions',
            'areasToImprove',
            'strongCount',
            'developingCount',
            'focusCount',
            'totalTopicsCount'
        ));
    }

    /**
     * Build topic accuracy items from real completed attempts and question answers.
     */
    protected function buildTopicItemsFromAttempts(Collection $attempts): Collection
    {
        if ($attempts->isEmpty()) {
            return collect();
        }

        $topicBuckets = [];
        $untaggedAttemptIds = [];

        foreach ($attempts as $attempt) {
            $paper = $attempt->paper;
            if (!$paper) {
                continue;
            }

            $resolvedTopic = $paper->topic ?: ($paper->subtopic?->parentTopic ?: $paper->subtopic);
            if (!$resolvedTopic) {
                $untaggedAttemptIds[] = $attempt->id;
                continue;
            }

            $pct = round(($attempt->score / $attempt->max_score) * 100);
            $subject = $paper->subject ?: $resolvedTopic->subject;
            $key = 'topic_' . $resolvedTopic->id;

            if (!isset($topicBuckets[$key])) {
                $topicBuckets[$key] = [
                    'id'          => $resolvedTopic->id,
                    'name'        => $resolvedTopic->name,
                    'subject_obj' => $subject,
                    'scores'      => [],
                ];
            }

            $topicBuckets[$key]['scores'][] = $pct;
        }

        // Fallback to question-level topics for multi-topic papers without paper.topic_id
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
                    continue; // Avoid mixing paper-level and question-level counts for the same topic
                }

                $subject = $question->subject ?: $resolvedTopic->subject;
                if (!isset($answerBuckets[$key])) {
                    $answerBuckets[$key] = [
                        'id'          => $resolvedTopic->id,
                        'name'        => $resolvedTopic->name,
                        'subject_obj' => $subject,
                        'earned'      => 0.0,
                        'possible'    => 0.0,
                        'count'       => 0,
                    ];
                }

                $maxMarks = (float) ($question->marks > 0 ? $question->marks : 1);
                $earned = $answer->marks_obtained !== null
                    ? (float) $answer->marks_obtained
                    : ($answer->is_correct ? $maxMarks : 0.0);

                $answerBuckets[$key]['earned'] += $earned;
                $answerBuckets[$key]['possible'] += $maxMarks;
                $answerBuckets[$key]['count']++;
            }

            foreach ($answerBuckets as $key => $bucket) {
                if ($bucket['possible'] > 0) {
                    $topicBuckets[$key] = [
                        'id'          => $bucket['id'],
                        'name'        => $bucket['name'],
                        'subject_obj' => $bucket['subject_obj'],
                        'scores'      => [round(($bucket['earned'] / $bucket['possible']) * 100)],
                        'attempts'    => $bucket['count'],
                    ];
                }
            }
        }

        return collect($topicBuckets)->map(function (array $bucket) {
            $accuracy = (int) round(collect($bucket['scores'])->avg());
            $accuracy = max(0, min(100, $accuracy));
            $meta = $this->resolveSubjectMeta($bucket['subject_obj']?->title ?? '');
            $status = $this->classifyAccuracy($accuracy);

            return [
                'id'            => $bucket['id'],
                'name'          => $bucket['name'],
                'subject_id'    => $bucket['subject_obj']?->id,
                'subject_key'   => $meta['key'],
                'subject_name'  => $meta['label'],
                'subject_badge' => $meta['badge'],
                'accuracy'      => $accuracy,
                'attempts'      => $bucket['attempts'] ?? count($bucket['scores']),
                'status'        => $status['key'],
                'status_label'  => $status['label'],
                'url'           => route('student.topics.subtopics', $bucket['id']),
            ];
        })->values();
    }

    /**
     * Build subject overview chart series and icons from real attempts/topics.
     */
    protected function buildSubjectOverview(Collection $attempts, Collection $topicItems): array
    {
        $subjects = Subject::where('is_active', true)->orderBy('id')->get();
        $overview = [];

        foreach ($subjects as $subject) {
            $meta = $this->resolveSubjectMeta($subject->title);

            $subjectAttempts = $attempts->filter(function ($attempt) use ($subject) {
                return (int) ($attempt->paper?->subject_id ?? 0) === (int) $subject->id;
            });

            if ($subjectAttempts->isNotEmpty()) {
                $score = (int) round($subjectAttempts->avg(fn($a) => ($a->score / $a->max_score) * 100));
            } else {
                $matchingTopics = $topicItems->where('subject_key', $meta['key']);
                $score = $matchingTopics->isNotEmpty() ? (int) round($matchingTopics->avg('accuracy')) : 0;
            }

            $overview[] = [
                'id'    => $subject->id,
                'key'   => $meta['key'],
                'title' => $meta['label'],
                'score' => max(0, min(100, $score)),
                'color' => $meta['color'],
                'color_light' => $meta['color_light'],
                'icon'  => $meta['icon'],
            ];
        }

        // Ensure subjects seen in attempts that might not be in active subjects still appear
        $existingKeys = collect($overview)->pluck('key')->all();
        foreach ($topicItems->groupBy('subject_key') as $key => $items) {
            if (!in_array($key, $existingKeys, true)) {
                $first = $items->first();
                $meta = $this->resolveSubjectMeta($first['subject_name']);
                $overview[] = [
                    'id'    => $first['subject_id'],
                    'key'   => $key,
                    'title' => $first['subject_name'],
                    'score' => (int) round($items->avg('accuracy')),
                    'color' => $meta['color'],
                    'color_light' => $meta['color_light'],
                    'icon'  => $meta['icon'],
                ];
            }
        }

        return $overview;
    }

    /**
     * Provide sample preview topic rows when a student has no completed attempts yet.
     */
    protected function buildSampleTopicItems(): Collection
    {
        $subjectsByKey = Subject::where('is_active', true)
            ->get()
            ->keyBy(fn($s) => $this->resolveSubjectMeta($s->title)['key']);

        $topicsByName = Topic::where(function ($q) {
            $q->whereNull('parent')->orWhere('parent', 0);
        })->get()->keyBy(fn($t) => strtolower(trim($t->name)));

        $curriculum = [
            ['name' => 'Comprehension',        'subject' => 'English',          'accuracy' => 88],
            ['name' => 'Number & Calculation', 'subject' => 'Maths',            'accuracy' => 84],
            ['name' => 'Word Relationships',   'subject' => 'Verbal Reasoning', 'accuracy' => 82],
            ['name' => 'Grammar',              'subject' => 'English',          'accuracy' => 80],
            ['name' => 'Data Handling',        'subject' => 'Maths',            'accuracy' => 80],
            ['name' => 'Problem Solving',      'subject' => 'Maths',            'accuracy' => 76],
            ['name' => 'Vocabulary',           'subject' => 'English',          'accuracy' => 68],
            ['name' => 'Sequences',            'subject' => 'Verbal Reasoning', 'accuracy' => 68],
            ['name' => 'Creative Writing',     'subject' => 'English',          'accuracy' => 64],
            ['name' => 'Shapes & Geometry',    'subject' => 'Maths',            'accuracy' => 58],
            ['name' => 'Logical Reasoning',    'subject' => 'Verbal Reasoning', 'accuracy' => 56],
            ['name' => 'Codes & Patterns',     'subject' => 'Verbal Reasoning', 'accuracy' => 52],
        ];

        return collect($curriculum)->map(function (array $item) use ($subjectsByKey, $topicsByName) {
            $meta = $this->resolveSubjectMeta($item['subject']);
            $status = $this->classifyAccuracy($item['accuracy']);
            $matchedTopic = $topicsByName->get(strtolower($item['name']));
            $matchedSubject = $subjectsByKey->get($meta['key']);

            $url = $matchedTopic
                ? route('student.topics.subtopics', $matchedTopic->id)
                : ($matchedSubject
                    ? route('student.assessments.topics', $matchedSubject->id)
                    : route('student.assessments'));

            return [
                'id'            => $matchedTopic?->id,
                'name'          => $item['name'],
                'subject_id'    => $matchedSubject?->id,
                'subject_key'   => $meta['key'],
                'subject_name'  => $meta['label'],
                'subject_badge' => $meta['badge'],
                'accuracy'      => $item['accuracy'],
                'attempts'      => 0,
                'status'        => $status['key'],
                'status_label'  => $status['label'],
                'url'           => $url,
            ];
        })->values();
    }

    /**
     * Build sample preview subject overview bars matching the sample topics.
     */
    protected function buildSampleSubjectOverview(Collection $topicItems): array
    {
        $definitions = [
            'english' => 'English',
            'maths'   => 'Maths',
            'vr'      => 'Verbal Reasoning',
        ];

        $overview = [];
        foreach ($definitions as $key => $title) {
            $meta = $this->resolveSubjectMeta($title);
            $matching = $topicItems->where('subject_key', $key);
            $score = $matching->isNotEmpty() ? (int) round($matching->avg('accuracy')) : 0;

            $overview[] = [
                'id'          => null,
                'key'         => $meta['key'],
                'title'       => $meta['label'],
                'score'       => $score,
                'color'       => $meta['color'],
                'color_light' => $meta['color_light'],
                'icon'        => $meta['icon'],
            ];
        }

        return $overview;
    }

    /**
     * Collect unique subject dropdown options.
     */
    protected function buildSubjectFilterOptions(Collection $topicItems, array $subjectOverview): array
    {
        $options = [];
        foreach ($subjectOverview as $item) {
            $options[$item['key']] = [
                'key'   => $item['key'],
                'label' => $item['title'],
            ];
        }

        foreach ($topicItems as $topic) {
            if (!isset($options[$topic['subject_key']])) {
                $options[$topic['subject_key']] = [
                    'key'   => $topic['subject_key'],
                    'label' => $topic['subject_name'],
                ];
            }
        }

        return array_values($options);
    }

    /**
     * Map an accuracy percentage to Strong / Developing / Focus tier.
     */
    protected function classifyAccuracy(int $accuracy): array
    {
        if ($accuracy >= self::STRONG_THRESHOLD) {
            return ['key' => 'strong', 'label' => 'Strong'];
        }

        if ($accuracy >= self::DEVELOPING_THRESHOLD) {
            return ['key' => 'developing', 'label' => 'Developing'];
        }

        return ['key' => 'focus', 'label' => 'Focus'];
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
                'label'       => 'English',
                'badge'       => 'English',
                'color'       => '#ef4444',
                'color_light' => '#ff6b6b',
                'icon'        => 'book-open',
            ];
        }

        if (str_contains($lower, 'math')) {
            return [
                'key'         => 'maths',
                'label'       => 'Maths',
                'badge'       => 'Maths',
                'color'       => '#2563eb',
                'color_light' => '#60a5fa',
                'icon'        => 'calculator',
            ];
        }

        if (str_contains($lower, 'non') || str_contains($lower, 'nvr')) {
            return [
                'key'         => 'nvr',
                'label'       => 'Non-Verbal Reasoning',
                'badge'       => 'NVR',
                'color'       => '#8b5cf6',
                'color_light' => '#a78bfa',
                'icon'        => 'shapes',
            ];
        }

        if (str_contains($lower, 'verbal') || $lower === 'vr') {
            return [
                'key'         => 'vr',
                'label'       => 'Verbal Reasoning',
                'badge'       => 'VR',
                'color'       => '#f59e0b',
                'color_light' => '#fbbf24',
                'icon'        => 'brain',
            ];
        }

        if (str_contains($lower, 'sci')) {
            return [
                'key'         => 'science',
                'label'       => 'Science',
                'badge'       => 'Science',
                'color'       => '#10b981',
                'color_light' => '#34d399',
                'icon'        => 'zap',
            ];
        }

        $cleanTitle = $title !== '' ? $title : 'General';
        return [
            'key'         => 'subject_' . preg_replace('/[^a-z0-9]+/', '_', $lower ?: 'general'),
            'label'       => $cleanTitle,
            'badge'       => $cleanTitle,
            'color'       => '#6366f1',
            'color_light' => '#818cf8',
            'icon'        => 'layers',
        ];
    }
}

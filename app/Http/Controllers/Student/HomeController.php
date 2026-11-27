<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Announcement;
use App\Models\MediaFile;
use App\Models\Paper;
use App\Models\PaperAttempt;
use App\Models\StudentAnswer;
use App\Models\StudentVideoProgress;
use App\Models\Subject;
use App\Models\User;
use App\Models\YearGroup;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $now = now();

        // 1. Targeted active announcements for this student
        $targetedAnnouncements = $this->getTargetedAnnouncements($user, $now);
        $unreadHighPriority = $targetedAnnouncements
            ->filter(fn($a) => $a->priority === 'high' && !$a->views->contains('user_id', $user->id))
            ->values();

        // 2. Visible papers & all attempts for this student
        $visiblePapers = Paper::visibleTo($user)
            ->with(['subject', 'topic', 'weeks'])
            ->withCount('questions')
            ->get();
        $visiblePaperIds = $visiblePapers->pluck('id');

        $userAttempts = PaperAttempt::where('user_id', $user->id)
            ->with(['paper.subject', 'paper.topic', 'paper.subtopic.parentTopic'])
            ->orderByRaw('COALESCE(completed_at, created_at) DESC')
            ->get();

        $completedPaperIds = $userAttempts->where('status', 'completed')->pluck('paper_id')->unique();
        $activeAttemptsByPaper = $userAttempts->whereIn('status', ['in_progress', 'paused'])->keyBy('paper_id');

        // 3. Visible published video lessons & student video progress
        $visibleVideos = $this->getVisibleVideos($user);
        $visibleVideoIds = $visibleVideos->pluck('id');

        $userVideoProgress = StudentVideoProgress::where('user_id', $user->id)->get();
        $completedVisibleVideoIds = $userVideoProgress
            ->whereIn('media_file_id', $visibleVideoIds)
            ->where('is_completed', true)
            ->pluck('media_file_id')
            ->unique();

        // 4. Subject cards (real per-student completion across visible papers & lessons)
        $subjectCards = $this->buildSubjectCards($visiblePapers, $visibleVideos, $completedPaperIds, $completedVisibleVideoIds);

        // 5. Focus Areas card (real lowest-scoring topics from student's completed attempts)
        $focusTopics = $this->buildFocusTopics($userAttempts);

        // 6. Upcoming & Important card (active/paused attempts, uncompleted visible papers, upcoming announcements)
        $upcomingItems = $this->buildUpcomingItems($visiblePapers, $completedPaperIds, $activeAttemptsByPaper, $targetedAnnouncements);

        // 7. Overall Progress card
        $overallTotalCount = $visiblePapers->count() + $visibleVideos->count();
        $overallCompletedCount = $completedPaperIds->intersect($visiblePaperIds)->count() + $completedVisibleVideoIds->count();
        $overallProgressPct = $overallTotalCount > 0 ? (int) round(($overallCompletedCount / $overallTotalCount) * 100) : 0;
        $overallDashLength = round(($overallProgressPct / 100) * 232.48, 1);

        // 8. Weekly Progress (Mon-Sun of current week)
        $weeklyActivity = $this->buildWeeklyActivity($userAttempts, $userVideoProgress);

        // 9. Learning Streak (continuous active days ending today or yesterday)
        $learningStreak = $this->calculateStreak($userAttempts, $userVideoProgress);

        return view('student.dashboard', compact(
            'unreadHighPriority',
            'subjectCards',
            'focusTopics',
            'upcomingItems',
            'overallTotalCount',
            'overallCompletedCount',
            'overallProgressPct',
            'overallDashLength',
            'weeklyActivity',
            'learningStreak'
        ));
    }

    /**
     * Fetch active announcements targeted to the logged-in student.
     */
    private function getTargetedAnnouncements(User $user, Carbon $now): Collection
    {
        $classIds = $user->classes()->pluck('classes.id')->toArray();
        $studentDetail = $user->studentDetail;
        $yearGroupId = null;
        if ($studentDetail && $studentDetail->group_year) {
            $yearGroupId = YearGroup::where('value', $studentDetail->group_year)->value('id');
        }

        return Announcement::with('views')
            ->where('is_draft', false)
            ->where('status', 'active')
            ->where(function ($q) use ($now) {
                $q->whereNull('show_from')->orWhere('show_from', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', $now);
            })
            ->where(function ($q) use ($user, $classIds, $yearGroupId) {
                $q->whereHas('targets', function ($t) use ($user) {
                    $t->where('target_type', 'user')->where('target_id', $user->id);
                });

                $q->orWhereHas('targets', function ($t) {
                    $t->where('target_type', 'all_active_students');
                });

                if (!empty($classIds)) {
                    $q->orWhereHas('targets', function ($t) use ($classIds) {
                        $t->where('target_type', 'class')->whereIn('target_id', $classIds);
                    });
                }

                if ($yearGroupId) {
                    $q->orWhereHas('targets', function ($t) use ($yearGroupId) {
                        $t->where('target_type', 'year_group')->where('target_id', $yearGroupId);
                    });
                }
            })
            ->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 WHEN 'low' THEN 3 END ASC")
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Fetch published video files visible to the logged-in student.
     */
    private function getVisibleVideos(User $user): Collection
    {
        $detail = $user->studentDetail;
        $classIds = $user->classes()->pluck('classes.id');

        $yearGroupIds = collect();
        if ($detail?->group_year) {
            $ygId = YearGroup::where('value', $detail->group_year)
                ->orWhere('title', $detail->group_year)
                ->value('id');
            if ($ygId) {
                $yearGroupIds->push($ygId);
            }
        }
        $classGroupYears = $user->classes()->pluck('classes.group_year')->filter();
        if ($classGroupYears->isNotEmpty()) {
            $yearGroupIds = $yearGroupIds->merge(
                YearGroup::whereIn('value', $classGroupYears)
                    ->orWhereIn('title', $classGroupYears)
                    ->pluck('id')
            );
        }
        $yearGroupIds = $yearGroupIds->unique()->filter()->values();

        $academicYears = collect();
        $rawYears = collect([$detail?->academic_year])->merge($user->classes()->pluck('classes.academic_year'))->filter();
        foreach ($rawYears as $val) {
            $academicYears->push($val);
            if (is_numeric($val)) {
                $ayName = AcademicYear::find($val)?->name;
                if ($ayName) {
                    $academicYears->push($ayName);
                }
            } else {
                $ayId = AcademicYear::where('name', $val)->value('id');
                if ($ayId) {
                    $academicYears->push((string) $ayId);
                }
            }
        }
        $academicYears = $academicYears->unique()->filter()->values();

        return MediaFile::where('publication_status', 'published')
            ->where(function ($q) use ($classIds) {
                $q->whereNull('class_id');
                if ($classIds->isNotEmpty()) {
                    $q->orWhereIn('class_id', $classIds);
                }
            })
            ->where(function ($q) use ($yearGroupIds) {
                $q->whereNull('year_group_id');
                if ($yearGroupIds->isNotEmpty()) {
                    $q->orWhereIn('year_group_id', $yearGroupIds);
                }
            })
            ->where(function ($q) use ($academicYears) {
                $q->whereNull('academic_year');
                if ($academicYears->isNotEmpty()) {
                    $q->orWhereIn('academic_year', $academicYears);
                }
            })
            ->get();
    }

    /**
     * Build subject banner cards from active subjects and real student completion.
     */
    private function buildSubjectCards(
        Collection $visiblePapers,
        Collection $visibleVideos,
        Collection $completedPaperIds,
        Collection $completedVisibleVideoIds
    ): Collection {
        return Subject::where('is_active', true)->orderBy('id')->get()->map(function ($subject) use (
            $visiblePapers,
            $visibleVideos,
            $completedPaperIds,
            $completedVisibleVideoIds
        ) {
            $subjPapers = $visiblePapers->where('subject_id', $subject->id);
            $subjVideos = $visibleVideos->where('subject_id', $subject->id);

            $totalItems = $subjPapers->count() + $subjVideos->count();
            $completedItems = $subjPapers->whereIn('id', $completedPaperIds)->count()
                + $subjVideos->whereIn('id', $completedVisibleVideoIds)->count();

            $progress = $totalItems > 0 ? (int) round(($completedItems / $totalItems) * 100) : 0;

            $lower = strtolower(trim($subject->title ?? ''));
            if (str_contains($lower, 'eng')) {
                $cardTheme = 'english';
            } elseif (str_contains($lower, 'math')) {
                $cardTheme = 'maths';
            } elseif (str_contains($lower, 'verbal') && !str_contains($lower, 'non')) {
                $cardTheme = 'vr';
            } elseif (str_contains($lower, 'non') || str_contains($lower, 'nvr')) {
                $cardTheme = 'nvr';
            } else {
                $cardTheme = 'maths';
            }

            $url = $subjVideos->isNotEmpty()
                ? route('student.videolessonslist', ['subject_id' => $subject->id])
                : ($subjPapers->isNotEmpty()
                    ? route('student.assessments.topics', $subject->id)
                    : route('student.videolessonscategories', ['subject_id' => $subject->id]));

            $subtitle = $subject->description
                ?: ($subjVideos->count() . ' ' . \Illuminate\Support\Str::plural('Lesson', $subjVideos->count())
                    . ' · ' . $subjPapers->count() . ' ' . \Illuminate\Support\Str::plural('Test', $subjPapers->count()));

            return [
                'id'       => $subject->id,
                'title'    => $subject->title,
                'subtitle' => $subtitle,
                'theme'    => $cardTheme,
                'progress' => $progress,
                'url'      => $url,
            ];
        });
    }

    /**
     * Build the top 3 lowest-scoring focus topics from the student's real completed attempts.
     */
    private function buildFocusTopics(Collection $userAttempts): Collection
    {
        $completedScored = $userAttempts->filter(fn($a) => $a->status === 'completed' && $a->max_score > 0);
        if ($completedScored->isEmpty()) {
            return collect();
        }

        $topicBuckets = [];
        $untaggedAttemptIds = [];

        foreach ($completedScored as $attempt) {
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
            $accuracy = (int) max(0, min(100, round(collect($bucket['scores'])->avg())));
            $subjTitle = $bucket['subject_obj']?->title ?? 'General';
            $lower = strtolower($subjTitle);

            if (str_contains($lower, 'eng')) {
                $styleKey = 'english';
                $color = '#FA2833';
            } elseif (str_contains($lower, 'math')) {
                $styleKey = 'maths';
                $color = '#2477F9';
            } elseif (str_contains($lower, 'verbal') || str_contains($lower, 'nvr') || $lower === 'vr') {
                $styleKey = 'vr';
                $color = '#F3A521';
            } else {
                $styleKey = 'maths';
                $color = '#2477F9';
            }

            return [
                'id'           => $bucket['id'],
                'topic_name'   => $bucket['name'],
                'subject_name' => $subjTitle,
                'style_key'    => $styleKey,
                'color'        => $color,
                'accuracy'     => $accuracy,
                'url'          => route('student.topics.subtopics', $bucket['id']),
            ];
        })->sortBy('accuracy')->values()->take(3);
    }

    /**
     * Build up to 4 real upcoming/actionable items (paused tests, pending assigned tests, notices).
     */
    private function buildUpcomingItems(
        Collection $visiblePapers,
        Collection $completedPaperIds,
        Collection $activeAttemptsByPaper,
        Collection $targetedAnnouncements
    ): Collection {
        $items = collect();

        // 1. Paused or in-progress attempts (resume first)
        foreach ($activeAttemptsByPaper as $attempt) {
            $paper = $attempt->paper;
            if (!$paper) {
                continue;
            }
            $date = $attempt->paused_at ?? $attempt->started_at ?? $attempt->created_at ?? now();
            $badge = $this->resolvePillMeta($paper->subject?->title ?? 'Test');

            $items->push([
                'day'       => $date->format('d'),
                'month'     => $date->format('M'),
                'box_class' => 'date-box-orange',
                'title'     => $paper->title,
                'subtitle'  => $attempt->status === 'paused' ? 'Paused test · Resume now' : 'In progress · Continue',
                'pill'      => $badge['label'],
                'pill_cls'  => $badge['class'],
                'url'       => route('student.attempts.take', $attempt->id),
            ]);
        }

        // 2. Uncompleted visible papers (prefer papers with upcoming week due dates)
        $pendingPapers = $visiblePapers
            ->filter(fn($p) => !$completedPaperIds->contains($p->id) && !$activeAttemptsByPaper->has($p->id))
            ->sortBy(function ($paper) {
                $dueDate = $paper->weeks->pluck('due_date')->filter()->sort()->first();
                return $dueDate ? Carbon::parse($dueDate)->timestamp : ($paper->created_at?->timestamp ?? PHP_INT_MAX);
            });

        foreach ($pendingPapers as $paper) {
            if ($items->count() >= 4) {
                break;
            }
            $dueDateRaw = $paper->weeks->pluck('due_date')->filter()->sort()->first();
            $date = $dueDateRaw ? Carbon::parse($dueDateRaw) : ($paper->created_at ?? now());
            $badge = $this->resolvePillMeta($paper->subject?->title ?? 'Paper');

            $qCount = (int) ($paper->questions_count ?? 0);
            $subParts = [];
            if ($qCount > 0) {
                $subParts[] = $qCount . ' ' . \Illuminate\Support\Str::plural('question', $qCount);
            }
            if ($paper->total_time) {
                $subParts[] = $paper->total_time . ' mins';
            }

            $targetUrl = $paper->topic_id
                ? route('student.topics.subtopics', $paper->topic_id)
                : ($paper->subject_id
                    ? route('student.assessments.topics', $paper->subject_id)
                    : route('student.assessments'));

            $items->push([
                'day'       => $date->format('d'),
                'month'     => $date->format('M'),
                'box_class' => $badge['box_class'],
                'title'     => $paper->title,
                'subtitle'  => !empty($subParts) ? implode(' · ', $subParts) : 'Pending assessment',
                'pill'      => $badge['label'],
                'pill_cls'  => $badge['class'],
                'url'       => $targetUrl,
            ]);
        }

        // 3. Active targeted announcements if room remains
        foreach ($targetedAnnouncements as $announcement) {
            if ($items->count() >= 4) {
                break;
            }
            $date = $announcement->show_from ?? $announcement->created_at ?? now();
            $items->push([
                'day'       => $date->format('d'),
                'month'     => $date->format('M'),
                'box_class' => $announcement->priority === 'high' ? 'date-box-red' : 'date-box-purple',
                'title'     => $announcement->title,
                'subtitle'  => $announcement->priority === 'high' ? 'Urgent announcement' : 'Notice from Aspire',
                'pill'      => 'Notice',
                'pill_cls'  => 'pill-mixed',
                'url'       => route('student.announcements'),
            ]);
        }

        return $items->take(4)->values();
    }

    /**
     * Build real Mon-Sun weekly activity counts for the current week.
     */
    private function buildWeeklyActivity(Collection $userAttempts, Collection $userVideoProgress): array
    {
        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->startOfDay();
        $completedAttempts = $userAttempts->where('status', 'completed');
        $completedVideos = $userVideoProgress->where('is_completed', true);

        $barColors = ['bar-color-red', 'bar-color-blue', 'bar-color-yellow', 'bar-color-blue', 'bar-color-red', 'bar-color-blue', 'bar-color-yellow'];
        $days = [];

        for ($i = 0; $i < 7; $i++) {
            $day = (clone $weekStart)->addDays($i);
            $dateStr = $day->toDateString();

            $attemptsCount = $completedAttempts->filter(function ($attempt) use ($dateStr) {
                $dt = $attempt->completed_at ?? $attempt->created_at;
                return $dt && $dt->toDateString() === $dateStr;
            })->count();

            $videosCount = $completedVideos->filter(function ($vp) use ($dateStr) {
                return $vp->updated_at && $vp->updated_at->toDateString() === $dateStr;
            })->count();

            $days[] = [
                'day'         => $day->format('D'),
                'count'       => $attemptsCount + $videosCount,
                'color_class' => $barColors[$i],
            ];
        }

        $maxCount = max(1, collect($days)->max('count'));

        return array_map(function (array $entry) use ($maxCount) {
            $entry['height'] = $entry['count'] > 0
                ? max(14, (int) round(($entry['count'] / $maxCount) * 62))
                : 4;
            return $entry;
        }, $days);
    }

    /**
     * Calculate continuous daily learning streak ending today or yesterday.
     */
    private function calculateStreak(Collection $userAttempts, Collection $userVideoProgress): int
    {
        $attemptDates = $userAttempts
            ->where('status', 'completed')
            ->map(fn($a) => ($a->completed_at ?? $a->created_at)?->toDateString())
            ->filter();

        $videoDates = $userVideoProgress
            ->map(fn($vp) => $vp->updated_at?->toDateString())
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

    private function resolvePillMeta(string $subjectTitle): array
    {
        $lower = strtolower($subjectTitle);
        if (str_contains($lower, 'eng')) {
            return ['label' => 'English', 'class' => 'pill-english', 'box_class' => 'date-box-red'];
        }
        if (str_contains($lower, 'math')) {
            return ['label' => 'Maths', 'class' => 'pill-maths', 'box_class' => 'date-box-blue'];
        }
        if (str_contains($lower, 'verbal') || str_contains($lower, 'nvr')) {
            return ['label' => 'VR', 'class' => 'pill-vr', 'box_class' => 'date-box-orange'];
        }
        return ['label' => $subjectTitle, 'class' => 'pill-mixed', 'box_class' => 'date-box-purple'];
    }
}

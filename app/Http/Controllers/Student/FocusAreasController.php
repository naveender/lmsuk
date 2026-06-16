<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Paper;
use App\Models\PaperAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FocusAreasController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->user();

        // Threshold settings from request or defaults
        $threshold     = (int) $request->input('threshold', 80);
        $averageType   = $request->input('average_type', 'average'); // average | first | last

        $subjects = Subject::where('is_active', true)->get();

        $subjectData = [];

        foreach ($subjects as $subject) {
            // Get all parent topics for this subject
            $topics = Topic::where('subject_id', $subject->id)
                ->where(function ($q) {
                    $q->whereNull('parent')->orWhere('parent', 0);
                })
                ->orderBy('name')
                ->get();

            $topicRows = [];

            foreach ($topics as $topic) {
                // All papers visible to this student under this topic
                $papers = Paper::visibleTo($student)
                    ->where('topic_id', $topic->id)
                    ->get();

                $paperIds = $papers->pluck('id');

                if ($paperIds->isEmpty()) {
                    continue;
                }

                $testsAvailable = $papers->count();

                // Completed attempts for this topic
                $completedAttempts = PaperAttempt::where('user_id', $student->id)
                    ->whereIn('paper_id', $paperIds)
                    ->where('status', 'completed')
                    ->orderBy('completed_at')
                    ->get();

                $testsAttempted = $completedAttempts->groupBy('paper_id')->count();
                $totalAttempts  = $completedAttempts->count();

                if ($totalAttempts === 0) {
                    continue; // skip topics with no attempts
                }

                // Calculate score percentages per attempt
                $scores = $completedAttempts->map(function ($a) {
                    if ($a->max_score > 0) {
                        return round(($a->score / $a->max_score) * 100);
                    }
                    return null;
                })->filter()->values();

                $average = $scores->count() > 0 ? round($scores->avg()) : null;

                // First attempt per paper
                $firstAttempts = $completedAttempts->groupBy('paper_id')->map(fn($g) => $g->first());
                $firstScores   = $firstAttempts->map(function ($a) {
                    if ($a->max_score > 0) {
                        return round(($a->score / $a->max_score) * 100);
                    }
                    return null;
                })->filter()->values();
                $firstAverage  = $firstScores->count() > 0 ? round($firstScores->avg()) : null;

                // Last attempt per paper
                $lastAttempts  = $completedAttempts->groupBy('paper_id')->map(fn($g) => $g->last());
                $lastScores    = $lastAttempts->map(function ($a) {
                    if ($a->max_score > 0) {
                        return round(($a->score / $a->max_score) * 100);
                    }
                    return null;
                })->filter()->values();
                $lastAverage   = $lastScores->count() > 0 ? round($lastScores->avg()) : null;

                // Determine which average to compare against threshold
                $compareScore = match ($averageType) {
                    'first' => $firstAverage,
                    'last'  => $lastAverage,
                    default => $average,
                };

                // Only show topics below the threshold
                if ($compareScore !== null && $compareScore >= $threshold) {
                    continue;
                }

                $topicRows[] = [
                    'name'          => $topic->name,
                    'available'     => $testsAvailable,
                    'attempted'     => $testsAttempted,
                    'total'         => $totalAttempts,
                    'average'       => $average,
                    'first_average' => $firstAverage,
                    'last_average'  => $lastAverage,
                ];
            }

            if (!empty($topicRows)) {
                $subjectData[] = [
                    'subject' => $subject,
                    'topics'  => $topicRows,
                ];
            }
        }

        return view('student.focusareas.index', compact('subjectData', 'threshold', 'averageType'));
    }
}

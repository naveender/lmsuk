<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Paper;
use App\Models\PaperAttempt;
use App\Models\StudentAnswer;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AssessmentController extends Controller
{
    /**
     * Display subject overview.
     */
    public function index()
    {
        $student = auth()->user();

        $subjects = Subject::where('is_active', true)->get()->map(function ($subject) use ($student) {
            $papers = Paper::visibleTo($student)
                ->where('subject_id', $subject->id)
                ->get();

            $subject->total_papers = $papers->count();
            $subject->tests_count = $papers->where('type', 'test')->count();
            $subject->exams_count = $papers->where('type', 'exam')->count();
            $subject->papers = $papers;

            // Compute actual completion progress based on attempts
            $paperIds = $papers->pluck('id');
            $completedCount = PaperAttempt::where('user_id', $student->id)
                ->whereIn('paper_id', $paperIds)
                ->where('status', 'completed')
                ->distinct('paper_id')
                ->count('paper_id');

            $pausedCount = PaperAttempt::where('user_id', $student->id)
                ->whereIn('paper_id', $paperIds)
                ->where('status', 'paused')
                ->distinct('paper_id')
                ->count('paper_id');

            $lastCompleted = PaperAttempt::where('user_id', $student->id)
                ->whereIn('paper_id', $paperIds)
                ->where('status', 'completed')
                ->orderBy('completed_at', 'desc')
                ->first();

            $subject->completed_papers_count = $completedCount;
            $subject->paused_papers_count = $pausedCount;
            $subject->last_completed_at = $lastCompleted ? $lastCompleted->completed_at : null;
            $subject->progress_percentage = $subject->total_papers > 0 
                ? round(($completedCount / $subject->total_papers) * 100) 
                : 0;

            return $subject;
        });

        return view('student.assessment.category-test-overview', compact('subjects'));
    }

    /**
     * Display list of topics for a subject.
     */
    public function topics($subjectId)
    {
        $student = auth()->user();
        $subject = Subject::findOrFail($subjectId);

        $query = Topic::where('subject_id', $subject->id)
            ->where(function ($q) {
                $q->whereNull('parent')->orWhere('parent', 0);
            });

        // Filter by topic name
        if (request()->filled('topic_name')) {
            $query->where('name', 'like', '%' . request('topic_name') . '%');
        }

        $topics = $query->paginate(10)->withQueryString();

        // Calculate statistics for each topic
        foreach ($topics as $topic) {
            // Get papers under this topic (and its subtopics) visible to this student
            $papers = Paper::visibleTo($student)
                ->where('topic_id', $topic->id)
                ->get();

            $topic->test_available = $papers->count();

            // Count how many distinct papers in this topic the student has completed
            $paperIds = $papers->pluck('id');
            $topic->test_attempted = PaperAttempt::where('user_id', $student->id)
                ->whereIn('paper_id', $paperIds)
                ->where('status', 'completed')
                ->distinct('paper_id')
                ->count('paper_id');

            $topic->easy_count = $papers->where('difficulty', 'easy')->count();
            $topic->medium_count = $papers->where('difficulty', 'medium')->count();
            $topic->hard_count = $papers->where('difficulty', 'hard')->count();
        }

        return view('student.assessment.assessments-topics', compact('subject', 'topics'));
    }

    /**
     * Display list of subtopics and papers for a parent topic.
     */
    public function subtopics($topicId)
    {
        $student = auth()->user();
        $topic = Topic::findOrFail($topicId);
        $subject = $topic->subject;

        // Fetch subtopics
        $subtopicsQuery = Topic::where('parent', $topic->id);
        if (request()->filled('subtopic_name')) {
            $subtopicsQuery->where('name', 'like', '%' . request('subtopic_name') . '%');
        }
        $subtopics = $subtopicsQuery->orderBy('name')->get();

        // Map papers to subtopics
        foreach ($subtopics as $subtopic) {
            $papersQuery = Paper::visibleTo($student)
                ->where('subtopic_id', $subtopic->id);

            if (request()->filled('paper_name')) {
                $papersQuery->where('title', 'like', '%' . request('paper_name') . '%');
            }

            $subtopic->papers = $papersQuery->get()->map(function ($paper) use ($student) {
                // Get all attempts for this student on this paper
                $paper->attempts = PaperAttempt::where('user_id', $student->id)
                    ->where('paper_id', $paper->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

                // Get active or paused attempts
                $paper->active_attempt = $paper->attempts->firstWhere('status', 'in_progress')
                    ?? $paper->attempts->firstWhere('status', 'paused');

                $paper->completed_attempts_count = $paper->attempts->where('status', 'completed')->count();

                return $paper;
            });
        }

        // Available subtopic list for dropdown filter
        $allSubtopics = Topic::where('parent', $topic->id)->orderBy('name')->get();

        return view('student.assessment.assessments-subtopics', compact('topic', 'subject', 'subtopics', 'allSubtopics'));
    }

    /**
     * Start or resume a test attempt.
     */
    public function startTest($paperId)
    {
        $student = auth()->user();
        $paper = Paper::findOrFail($paperId);

        // Check if paper is visible to student
        if (!Paper::visibleTo($student)->where('id', $paperId)->exists()) {
            abort(403, 'Unauthorized paper access.');
        }

        // Check for active or paused attempts
        $activeAttempt = PaperAttempt::where('user_id', $student->id)
            ->where('paper_id', $paperId)
            ->whereIn('status', ['in_progress', 'paused'])
            ->first();

        if ($activeAttempt) {
            return redirect()->route('student.attempts.take', $activeAttempt);
        }

        // Calculate max score
        $questionsCount = $paper->questions()->count();
        $maxScore = 0;
        foreach ($paper->questions as $q) {
            $maxScore += $q->pivot->marks ?? $paper->default_marks ?? 1;
        }

        // Create new attempt
        $attempt = PaperAttempt::create([
            'user_id' => $student->id,
            'paper_id' => $paper->id,
            'status' => 'in_progress',
            'time_spent' => 0,
            'started_at' => now(),
            'total_questions' => $questionsCount,
            'max_score' => $maxScore,
        ]);

        return redirect()->route('student.attempts.take', $attempt);
    }

    /**
     * Show test taking interface.
     */
    public function takeTest($attemptId)
    {
        $student = auth()->user();
        $attempt = PaperAttempt::with(['paper.questions.options'])->findOrFail($attemptId);

        if ($attempt->user_id !== $student->id) {
            abort(403, 'Unauthorized access to this test attempt.');
        }

        if ($attempt->status === 'completed') {
            return redirect()->route('student.attempts.result', $attempt);
        }

        // Resume if paused
        if ($attempt->status === 'paused') {
            $now = now();
            $attempt->update([
                'status' => 'in_progress',
                'started_at' => $now,
            ]);
            $attempt->started_at = $now;
            $attempt->status = 'in_progress';
        }

        // Load existing answers
        $answers = StudentAnswer::where('paper_attempt_id', $attempt->id)
            ->get()
            ->keyBy('question_id');

        // Calculate remaining seconds
        $remainingSeconds = null;
        if ($attempt->paper->total_time > 0) {
            $timeLimitSeconds = $attempt->paper->total_time * 60;
            $timeSpentSoFar = max(0, $attempt->time_spent);
            $elapsedInSession = (int) $attempt->started_at->diffInSeconds(now());
            
            $remainingSeconds = $timeLimitSeconds - ($timeSpentSoFar + $elapsedInSession);
            if ($remainingSeconds < 0) {
                $remainingSeconds = 0;
            }
        }

        return view('student.assessment.take-test', compact('attempt', 'answers', 'remainingSeconds'));
    }

    /**
     * Save answers (AJAX endpoint).
     */
    public function saveTest(Request $request, $attemptId)
    {
        $attempt = PaperAttempt::findOrFail($attemptId);
        if ($attempt->user_id !== auth()->id() || $attempt->status === 'completed') {
            return response()->json(['error' => 'Unauthorized or completed attempt.'], 403);
        }

        $this->saveAnswers($attempt, $request->input('answers', []));

        return response()->json(['success' => true]);
    }

    /**
     * Pause a test attempt.
     */
    public function pauseTest(Request $request, $attemptId)
    {
        $attempt = PaperAttempt::findOrFail($attemptId);
        if ($attempt->user_id !== auth()->id() || $attempt->status === 'completed') {
            abort(403);
        }

        // Calculate time spent
        $elapsed = (int) $attempt->started_at->diffInSeconds(now());
        $attempt->time_spent = max(0, $attempt->time_spent) + $elapsed;

        // Save answers
        $this->saveAnswers($attempt, $request->input('answers', []));

        $attempt->status = 'paused';
        $attempt->paused_at = now();
        $attempt->save();

        return redirect()
            ->route('student.topics.subtopics', $attempt->paper->topic_id)
            ->with('success', 'Test paused successfully. You can resume it later!');
    }

    /**
     * Submit/Complete a test attempt.
     */
    public function submitTest(Request $request, $attemptId)
    {
        $attempt = PaperAttempt::findOrFail($attemptId);
        if ($attempt->user_id !== auth()->id() || $attempt->status === 'completed') {
            abort(403);
        }

        // Calculate time spent
        $elapsed = (int) $attempt->started_at->diffInSeconds(now());
        $attempt->time_spent = max(0, $attempt->time_spent) + $elapsed;

        // Save answers
        $this->saveAnswers($attempt, $request->input('answers', []));

        // Evaluate score
        $answers = StudentAnswer::where('paper_attempt_id', $attempt->id)->get();
        $correctCount = $answers->where('is_correct', true)->count();
        $score = $answers->sum('marks_obtained');

        $attempt->score = $score;
        $attempt->correct_answers = $correctCount;
        $attempt->status = 'completed';
        $attempt->completed_at = now();
        $attempt->save();

        return redirect()
            ->route('student.attempts.result', $attempt)
            ->with('success', 'Test submitted successfully!');
    }

    /**
     * Display attempt result summary.
     */
    public function result($attemptId)
    {
        $attempt = PaperAttempt::with(['paper.questions.options', 'answers.question'])->findOrFail($attemptId);

        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }

        $answers = StudentAnswer::where('paper_attempt_id', $attempt->id)
            ->get()
            ->keyBy('question_id');

        return view('student.assessment.result', compact('attempt', 'answers'));
    }

    /**
     * Helper to save answers and auto-grade choices.
     */
    private function saveAnswers(PaperAttempt $attempt, array $answersData)
    {
        $paper = $attempt->paper;

        foreach ($paper->questions as $question) {
            $qId = $question->id;
            
            // Check if there's any answer submitted for this question
            if (!array_key_exists($qId, $answersData)) {
                continue;
            }

            $ansData = $answersData[$qId];
            $selectedOptId = $ansData['selected_option_id'] ?? null;
            $selectedOptIds = $ansData['selected_options'] ?? null;
            $ansText = $ansData['answer_text'] ?? null;
            $qTimeSpent = $ansData['time_spent'] ?? 0;
            $qIsFlagged = isset($ansData['is_flagged']) ? (bool)$ansData['is_flagged'] : false;
            $qConfidence = $ansData['confidence'] ?? null;

            // Get paper marks for this question
            $paperQuestion = DB::table('paper_question')
                ->where('paper_id', $paper->id)
                ->where('question_id', $qId)
                ->first();
            $qMarks = $paperQuestion->marks ?? $question->marks ?? $paper->default_marks ?? 1;

            $isCorrect = false;
            $marksObtained = 0;

            // Auto-grading based on question type
            if ($question->type === 'single_choice_radio' || $question->type === 'single_choice_dropdown') {
                if ($selectedOptId) {
                    $option = QuestionOption::find($selectedOptId);
                    if ($option && $option->is_correct) {
                        $isCorrect = true;
                        $marksObtained = $qMarks;
                    }
                }
            } elseif ($question->type === 'multiple_choice') {
                if (is_array($selectedOptIds) && count($selectedOptIds) > 0) {
                    $correctOptIds = QuestionOption::where('question_id', $qId)
                        ->where('is_correct', true)
                        ->pluck('id')
                        ->toArray();
                    
                    sort($selectedOptIds);
                    sort($correctOptIds);
                    if (!empty($correctOptIds) && $selectedOptIds === $correctOptIds) {
                        $isCorrect = true;
                        $marksObtained = $qMarks;
                    }
                }
            } elseif ($question->type === 'fill_in_the_blanks' || $question->type === 'matching_text') {
                if ($ansText !== null) {
                    $correctOption = QuestionOption::where('question_id', $qId)
                        ->where('is_correct', true)
                        ->first();
                    if ($correctOption && strtolower(trim($ansText)) === strtolower(trim($correctOption->option_text))) {
                        $isCorrect = true;
                        $marksObtained = $qMarks;
                    }
                }
            }

            // Save/Update response
            StudentAnswer::updateOrCreate(
                [
                    'paper_attempt_id' => $attempt->id,
                    'question_id' => $qId,
                ],
                [
                    'selected_option_id' => $selectedOptId ?: null,
                    'selected_options' => is_array($selectedOptIds) ? $selectedOptIds : null,
                    'answer_text' => $ansText,
                    'is_correct' => $isCorrect,
                    'marks_obtained' => $marksObtained,
                    'time_spent' => $qTimeSpent,
                    'is_flagged' => $qIsFlagged,
                    'confidence' => $qConfidence,
                ]
            );
        }
    }
}

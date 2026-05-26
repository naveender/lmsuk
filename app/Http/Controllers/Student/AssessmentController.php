<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Paper;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function index()
    {
        $student = auth()->user();

        // Fetch active subjects and map their papers visible to the current student
        $subjects = Subject::where('is_active', true)->get()->map(function ($subject) use ($student) {
            $papers = Paper::visibleTo($student)
                ->where('subject_id', $subject->id)
                ->get();

            $subject->total_papers = $papers->count();
            $subject->tests_count = $papers->where('type', 'test')->count();
            $subject->exams_count = $papers->where('type', 'exam')->count();
            $subject->papers = $papers;

            return $subject;
        });

        return view('student.assessment.category-test-overview', compact('subjects'));
    }
}

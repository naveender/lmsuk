<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Classes;
use App\Models\Question;
use App\Models\Paper;
use App\Models\Subject;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'students'  => User::where('role', 'student')->count(),
            'parents'   => User::where('role', 'parent')->count(),
            'tutors'    => User::where('role', 'tutor')->count(),
            'questions' => Question::count(),
            'papers'    => Paper::count(),
            'classes'   => Classes::count(),
        ];

        // Class enrollment stats for chart
        $classStats = Classes::withCount('students')
            ->orderByDesc('students_count')
            ->take(5)
            ->get();

        // Subject questions distribution stats for chart
        $subjectStats = Subject::withCount('questions')
            ->orderByDesc('questions_count')
            ->get();

        return view('admin.dashboard', compact('stats', 'classStats', 'subjectStats'));
    }
}

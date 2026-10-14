<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use App\Models\Subject;
use App\Models\Classes;
use App\Models\YearGroup;
use App\Models\AcademicYear;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class HomeworkController extends Controller
{
    /**
     * Display a listing of homeworks with filtering & statistics.
     */
    public function index(Request $request)
    {
        $query = Homework::with(['subject', 'topic', 'subtopic', 'class', 'yearGroup', 'user', 'courses']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('year_group_id')) {
            $query->where('year_group_id', $request->year_group_id);
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->filled('course_id')) {
            $query->whereHas('courses', function ($q) use ($request) {
                $q->where('courses.id', $request->course_id);
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $homeworks = $query->latest()->paginate(10)->withQueryString();

        // Stats
        $totalHomeworks = Homework::count();
        $activeCount = Homework::where('is_active', true)->count();
        $inactiveCount = Homework::where('is_active', false)->count();
        $filesCount = Homework::whereNotNull('file_path')->where('file_path', '!=', '')->count();
        $coursesLinkedCount = DB::table('course_homework')->distinct('homework_id')->count('homework_id');

        // Lists for filters
        $subjects = Subject::where('is_active', true)->orderBy('title')->get();
        $classes = Classes::where('is_active', true)->orderBy('name')->get();
        $yearGroups = YearGroup::where('is_active', true)->orderBy('title')->get();
        $academicYears = AcademicYear::where('is_active', true)->orderBy('name', 'desc')->get();
        $courses = Course::where('is_active', true)->orderBy('name')->get();

        return view('admin.homeworks.index', compact(
            'homeworks',
            'totalHomeworks',
            'activeCount',
            'inactiveCount',
            'filesCount',
            'coursesLinkedCount',
            'subjects',
            'classes',
            'yearGroups',
            'academicYears',
            'courses'
        ));
    }

    /**
     * Show the form for creating a new homework.
     */
    public function create()
    {
        return view('admin.homeworks.create');
    }

    /**
     * Show the form for editing the specified homework.
     */
    public function edit(Homework $homework)
    {
        return view('admin.homeworks.edit', compact('homework'));
    }

    /**
     * Display the specified homework details.
     */
    public function show(Homework $homework, Request $request)
    {
        $homework->load(['subject', 'topic', 'subtopic', 'class', 'yearGroup', 'user', 'courses']);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'homework' => $homework,
                'subject_name' => $homework->subject->title ?? '—',
                'topic_name' => $homework->topic->name ?? '—',
                'class_name' => $homework->class->name ?? '—',
                'year_group_name' => $homework->yearGroup->title ?? '—',
                'creator_name' => $homework->user->name ?? 'Admin',
            ]);
        }
        return redirect()->route('admin.homeworks.edit', $homework->id);
    }

    /**
     * Delete the homework assignment and its uploaded files.
     */
    public function destroy(Homework $homework, Request $request)
    {
        try {
            if ($homework->file_path && Storage::disk('public')->exists($homework->file_path)) {
                Storage::disk('public')->delete($homework->file_path);
            }

            $homework->courses()->detach();
            $homework->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Homework deleted successfully.'
                ]);
            }

            return redirect()->route('admin.homeworks.index')->with('success', 'Homework deleted successfully.');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting homework: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('admin.homeworks.index')->with('error', 'Error deleting homework: ' . $e->getMessage());
        }
    }

    /**
     * Toggle homework active/inactive status.
     */
    public function toggleStatus(Homework $homework)
    {
        $homework->is_active = !$homework->is_active;
        $homework->save();

        $statusStr = $homework->is_active ? 'activated and is now visible to eligible students' : 'deactivated and hidden from students';
        return redirect()->back()->with('success', "Homework \"{$homework->title}\" has been {$statusStr}.");
    }

    /**
     * Download the homework attached file.
     */
    public function download(Homework $homework)
    {
        if (!$homework->file_path || !Storage::disk('public')->exists($homework->file_path)) {
            return redirect()->back()->with('error', 'The requested file does not exist or has been deleted.');
        }

        $downloadName = $homework->file_name ?: basename($homework->file_path);

        return Storage::disk('public')->download($homework->file_path, $downloadName);
    }
}

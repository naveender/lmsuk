<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Paper;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
    public function index(Request $request)
    {
        $query = Course::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $courses = $query->latest()->paginate(10);

        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        return view('admin.courses.create');
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Course::create($validated);

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $course->update($validated);

        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully.');
    }

    /**
     * Show the paper management page for a specific course.
     */
    public function managePapers(Course $course)
    {
        // Get papers assigned to this course ordered by week
        $coursePapers = $course->papers()->orderBy('course_paper.week')->get();

        // Get all papers to populate the selection dropdown
        $allPapers = Paper::orderBy('title')->get();

        return view('admin.courses.manage_papers', compact('course', 'coursePapers', 'allPapers'));
    }

    /**
     * Add a paper to the course with a weekly parameter.
     */
    public function addPaper(Request $request, Course $course)
    {
        $request->validate([
            'paper_id' => 'required|exists:papers,id',
            'week' => 'required|integer|min:1',
        ]);

        if ($course->papers()->where('paper_id', $request->paper_id)->exists()) {
            // Update the week if already assigned
            $course->papers()->updateExistingPivot($request->paper_id, ['week' => $request->week]);
            $message = 'Paper week updated successfully.';
        } else {
            // Add new paper-course relationship
            $course->papers()->attach($request->paper_id, ['week' => $request->week]);
            $message = 'Paper added to course successfully.';
        }

        return redirect()->route('admin.courses.papers', $course->id)->with('success', $message);
    }

    /**
     * Remove a paper from the course.
     */
    public function removePaper(Course $course, Paper $paper)
    {
        $course->papers()->detach($paper->id);

        return redirect()->route('admin.courses.papers', $course->id)->with('success', 'Paper removed from course successfully.');
    }
}

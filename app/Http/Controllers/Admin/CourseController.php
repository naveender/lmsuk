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
        // Get papers assigned to this course ordered by week_id / week name / due date
        $coursePapers = $course->papers()
            ->leftJoin('weeks', 'course_paper.week_id', '=', 'weeks.id')
            ->select('papers.*', 'weeks.name as week_name', 'course_paper.week as pivot_week', 'course_paper.week_id as pivot_week_id')
            ->orderBy('weeks.due_date')
            ->orderBy('weeks.name')
            ->get();

        // Get all papers to populate the selection dropdown
        $allPapers = Paper::orderBy('title')->get();

        // Get all weeks for this course
        $weeks = $course->weeks()->orderBy('name')->get();

        return view('admin.courses.manage_papers', compact('course', 'coursePapers', 'allPapers', 'weeks'));
    }

    /**
     * Add a paper to the course with a weekly parameter.
     */
    public function addPaper(Request $request, Course $course)
    {
        $request->validate([
            'paper_id' => 'required|exists:papers,id',
            'week_mode' => 'required|in:existing,new',
            'week_id' => 'required_if:week_mode,existing|nullable|exists:weeks,id',
            'new_week_name' => 'required_if:week_mode,new|nullable|string|max:255',
            'new_week_due_date' => 'nullable|date',
        ]);

        $weekId = null;
        $weekName = '';

        if ($request->week_mode === 'existing') {
            $weekId = $request->week_id;
            $weekModel = \App\Models\Week::find($weekId);
            $weekName = $weekModel ? $weekModel->name : '';
        } else {
            $weekModel = \App\Models\Week::create([
                'course_id' => $course->id,
                'name' => $request->new_week_name,
                'due_date' => $request->new_week_due_date ?: null,
            ]);
            $weekId = $weekModel->id;
            $weekName = $weekModel->name;
        }

        $weekNumber = 1;
        if (preg_match('/(\d+)/', $weekName, $matches)) {
            $weekNumber = (int) $matches[1];
        }

        if ($course->papers()->where('paper_id', $request->paper_id)->exists()) {
            // Update the week if already assigned
            $course->papers()->updateExistingPivot($request->paper_id, [
                'week' => $weekNumber,
                'week_id' => $weekId
            ]);
            $message = 'Paper week updated successfully.';
        } else {
            // Add new paper-course relationship
            $course->papers()->attach($request->paper_id, [
                'week' => $weekNumber,
                'week_id' => $weekId
            ]);
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

    /**
     * Show the media management page for a specific course.
     */
    public function manageMedia(Course $course)
    {
        // Get media files assigned to this course ordered by week_id / week name
        $courseMedia = $course->mediaFiles()
            ->leftJoin('weeks', 'course_media_file.week_id', '=', 'weeks.id')
            ->select('media_files.*', 'weeks.name as week_name', 'course_media_file.week as pivot_week', 'course_media_file.week_id as pivot_week_id')
            ->orderBy('weeks.due_date')
            ->orderBy('weeks.name')
            ->get();

        // Get all media files to populate the selection dropdown
        $allMedia = MediaFile::orderBy('title')->get();

        // Get all weeks for this course
        $weeks = $course->weeks()->orderBy('name')->get();

        return view('admin.courses.manage_media', compact('course', 'courseMedia', 'allMedia', 'weeks'));
    }

    /**
     * Add a media file to the course with a weekly parameter.
     */
    public function addMedia(Request $request, Course $course)
    {
        $request->validate([
            'media_file_id' => 'required|exists:media_files,id',
            'week_mode' => 'required|in:existing,new',
            'week_id' => 'required_if:week_mode,existing|nullable|exists:weeks,id',
            'new_week_name' => 'required_if:week_mode,new|nullable|string|max:255',
            'new_week_due_date' => 'nullable|date',
        ]);

        $weekId = null;
        $weekName = '';

        if ($request->week_mode === 'existing') {
            $weekId = $request->week_id;
            $weekModel = \App\Models\Week::find($weekId);
            $weekName = $weekModel ? $weekModel->name : '';
        } else {
            $weekModel = \App\Models\Week::create([
                'course_id' => $course->id,
                'name' => $request->new_week_name,
                'due_date' => $request->new_week_due_date ?: null,
            ]);
            $weekId = $weekModel->id;
            $weekName = $weekModel->name;
        }

        $weekNumber = 1;
        if (preg_match('/(\d+)/', $weekName, $matches)) {
            $weekNumber = (int) $matches[1];
        }

        if ($course->mediaFiles()->where('media_file_id', $request->media_file_id)->exists()) {
            // Update the week if already assigned
            $course->mediaFiles()->updateExistingPivot($request->media_file_id, [
                'week' => $weekNumber,
                'week_id' => $weekId
            ]);
            $message = 'Media week updated successfully.';
        } else {
            // Add new media-course relationship
            $course->mediaFiles()->attach($request->media_file_id, [
                'week' => $weekNumber,
                'week_id' => $weekId
            ]);
            $message = 'Media added to course successfully.';
        }

        return redirect()->route('admin.courses.media', $course->id)->with('success', $message);
    }

    /**
     * Remove a media file from the course.
     */
    public function removeMedia(Course $course, MediaFile $mediaFile)
    {
        $course->mediaFiles()->detach($mediaFile->id);

        return redirect()->route('admin.courses.media', $course->id)->with('success', 'Media file removed from course successfully.');
    }
}

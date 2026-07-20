<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\MediaFile;
use App\Models\Subject;
use App\Models\StudentVideoProgress;
use App\Models\YearGroup;
use Illuminate\Http\Request;

class LessonsController extends Controller
{
    /**
     * Video Lessons Categories page — shows subject cards with video counts.
     * Only "General Video File" media (not assigned to any course weekly schedule)
     * that match the student's class, group year, and academic year are visible.
     */
    public function index()
    {
        $student = auth()->user();
        $detail = $student->studentDetail;

        // Resolve student visibility parameters
        $classIds = $student->classes()->pluck('classes.id');
        $groupYearName = $detail?->group_year;
        $academicYearVal = $detail?->academic_year;

        $yearGroupId = null;
        if ($groupYearName) {
            $yearGroupId = YearGroup::where('value', $groupYearName)
                ->orWhere('title', $groupYearName)
                ->value('id');
        }

        // Build base query for general (unassigned) published video files
        $baseQuery = MediaFile::where('publication_status', 'published')
            ->whereDoesntHave('courses');

        // Apply visibility filters
        $baseQuery->where(function ($q) use ($classIds) {
            if ($classIds->isNotEmpty()) {
                $q->whereNull('class_id')->orWhereIn('class_id', $classIds);
            } else {
                $q->whereNull('class_id');
            }
        });

        $baseQuery->where(function ($q) use ($yearGroupId) {
            if ($yearGroupId) {
                $q->whereNull('year_group_id')->orWhere('year_group_id', $yearGroupId);
            } else {
                $q->whereNull('year_group_id');
            }
        });

        $baseQuery->where(function ($q) use ($academicYearVal) {
            if ($academicYearVal) {
                $q->whereNull('academic_year')->orWhere('academic_year', $academicYearVal);
            } else {
                $q->whereNull('academic_year');
            }
        });

        // Get all matching video IDs
        $visibleMediaIds = (clone $baseQuery)->pluck('id');

        // Get subjects that have at least one visible video
        $subjects = Subject::where('is_active', true)
            ->whereHas('mediaFiles', function ($q) use ($visibleMediaIds) {
                $q->whereIn('media_files.id', $visibleMediaIds);
            })
            ->withCount(['mediaFiles' => function ($q) use ($visibleMediaIds) {
                $q->whereIn('media_files.id', $visibleMediaIds);
            }])
            ->get();

        // Load student's total progress per subject
        $progressBySubject = [];
        foreach ($subjects as $subject) {
            $subjectVideoIds = MediaFile::where('subject_id', $subject->id)
                ->whereIn('id', $visibleMediaIds)
                ->pluck('id');

            $completed = StudentVideoProgress::where('user_id', $student->id)
                ->whereIn('media_file_id', $subjectVideoIds)
                ->where('is_completed', true)
                ->count();

            $progressBySubject[$subject->id] = [
                'total' => $subjectVideoIds->count(),
                'completed' => $completed,
                'percent' => $subjectVideoIds->count() > 0
                    ? round(($completed / $subjectVideoIds->count()) * 100)
                    : 0,
            ];
        }

        return view('student.lessons.video-lessons-category', compact('subjects', 'progressBySubject'));
    }

    /**
     * Video Lessons List page — shows all videos for a specific subject.
     */
    public function lessionlist(Request $request)
    {
        $student = auth()->user();
        $detail = $student->studentDetail;
        $subjectId = $request->query('subject_id');

        $subject = Subject::findOrFail($subjectId);

        // Resolve student visibility parameters
        $classIds = $student->classes()->pluck('classes.id');
        $groupYearName = $detail?->group_year;
        $academicYearVal = $detail?->academic_year;

        $yearGroupId = null;
        if ($groupYearName) {
            $yearGroupId = YearGroup::where('value', $groupYearName)
                ->orWhere('title', $groupYearName)
                ->value('id');
        }

        // Query general (unassigned) published videos for this subject
        $mediaQuery = MediaFile::where('publication_status', 'published')
            ->where('subject_id', $subjectId)
            ->whereDoesntHave('courses');

        // Apply visibility filters
        $mediaQuery->where(function ($q) use ($classIds) {
            if ($classIds->isNotEmpty()) {
                $q->whereNull('class_id')->orWhereIn('class_id', $classIds);
            } else {
                $q->whereNull('class_id');
            }
        });

        $mediaQuery->where(function ($q) use ($yearGroupId) {
            if ($yearGroupId) {
                $q->whereNull('year_group_id')->orWhere('year_group_id', $yearGroupId);
            } else {
                $q->whereNull('year_group_id');
            }
        });

        $mediaQuery->where(function ($q) use ($academicYearVal) {
            if ($academicYearVal) {
                $q->whereNull('academic_year')->orWhere('academic_year', $academicYearVal);
            } else {
                $q->whereNull('academic_year');
            }
        });

        $mediaFiles = $mediaQuery->orderBy('created_at', 'desc')->paginate(12);

        // Load student watch progress
        $videoProgressMap = StudentVideoProgress::where('user_id', $student->id)
            ->whereIn('media_file_id', $mediaFiles->pluck('id'))
            ->get()
            ->keyBy('media_file_id');

        return view('student.lessons.lessons-videos-list', compact('subject', 'mediaFiles', 'videoProgressMap'));
    }
}

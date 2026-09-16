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

        // 1. Resolve Year Group IDs (from student details and assigned classes)
        $yearGroupIds = collect();
        if ($yearGroupId) {
            $yearGroupIds->push($yearGroupId);
        }
        $classGroupYears = $student->classes()->pluck('classes.group_year')->filter();
        if ($classGroupYears->isNotEmpty()) {
            $classYgIds = YearGroup::whereIn('value', $classGroupYears)
                ->orWhereIn('title', $classGroupYears)
                ->pluck('id');
            $yearGroupIds = $yearGroupIds->merge($classYgIds);
        }
        $yearGroupIds = $yearGroupIds->unique()->filter()->values();

        // 2. Resolve Academic Years (both names like "2026-2027" and IDs like 4, from student details and assigned classes)
        $studentAcademicYears = collect();
        if ($academicYearVal) {
            $studentAcademicYears->push($academicYearVal);
            if (is_numeric($academicYearVal)) {
                $ayName = \App\Models\AcademicYear::find($academicYearVal)?->name;
                if ($ayName) $studentAcademicYears->push($ayName);
            } else {
                $ayId = \App\Models\AcademicYear::where('name', $academicYearVal)->value('id');
                if ($ayId) $studentAcademicYears->push((string)$ayId);
            }
        }
        $classAcademicYears = $student->classes()->pluck('classes.academic_year')->filter();
        foreach ($classAcademicYears as $cay) {
            $studentAcademicYears->push($cay);
            if (is_numeric($cay)) {
                $ayName = \App\Models\AcademicYear::find($cay)?->name;
                if ($ayName) $studentAcademicYears->push($ayName);
            } else {
                $ayId = \App\Models\AcademicYear::where('name', $cay)->value('id');
                if ($ayId) $studentAcademicYears->push((string)$ayId);
            }
        }
        $studentAcademicYears = $studentAcademicYears->unique()->filter()->values();

        // Build base query for general (unassigned) published video files
        $baseQuery = MediaFile::where('publication_status', 'published')
            ->whereDoesntHave('courses');

        // Apply visibility filters
        $baseQuery->where(function ($q) use ($classIds) {
            $q->whereNull('class_id');
            if ($classIds->isNotEmpty()) {
                $q->orWhereIn('class_id', $classIds);
            }
        });

        $baseQuery->where(function ($q) use ($yearGroupIds) {
            $q->whereNull('year_group_id');
            if ($yearGroupIds->isNotEmpty()) {
                $q->orWhereIn('year_group_id', $yearGroupIds);
            }
        });

        $baseQuery->where(function ($q) use ($studentAcademicYears) {
            $q->whereNull('academic_year');
            if ($studentAcademicYears->isNotEmpty()) {
                $q->orWhereIn('academic_year', $studentAcademicYears);
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

        // 1. Resolve Year Group IDs (from student details and assigned classes)
        $yearGroupIds = collect();
        if ($yearGroupId) {
            $yearGroupIds->push($yearGroupId);
        }
        $classGroupYears = $student->classes()->pluck('classes.group_year')->filter();
        if ($classGroupYears->isNotEmpty()) {
            $classYgIds = YearGroup::whereIn('value', $classGroupYears)
                ->orWhereIn('title', $classGroupYears)
                ->pluck('id');
            $yearGroupIds = $yearGroupIds->merge($classYgIds);
        }
        $yearGroupIds = $yearGroupIds->unique()->filter()->values();

        // 2. Resolve Academic Years (both names like "2026-2027" and IDs like 4, from student details and assigned classes)
        $studentAcademicYears = collect();
        if ($academicYearVal) {
            $studentAcademicYears->push($academicYearVal);
            if (is_numeric($academicYearVal)) {
                $ayName = \App\Models\AcademicYear::find($academicYearVal)?->name;
                if ($ayName) $studentAcademicYears->push($ayName);
            } else {
                $ayId = \App\Models\AcademicYear::where('name', $academicYearVal)->value('id');
                if ($ayId) $studentAcademicYears->push((string)$ayId);
            }
        }
        $classAcademicYears = $student->classes()->pluck('classes.academic_year')->filter();
        foreach ($classAcademicYears as $cay) {
            $studentAcademicYears->push($cay);
            if (is_numeric($cay)) {
                $ayName = \App\Models\AcademicYear::find($cay)?->name;
                if ($ayName) $studentAcademicYears->push($ayName);
            } else {
                $ayId = \App\Models\AcademicYear::where('name', $cay)->value('id');
                if ($ayId) $studentAcademicYears->push((string)$ayId);
            }
        }
        $studentAcademicYears = $studentAcademicYears->unique()->filter()->values();

        // Query general (unassigned) published videos for this subject
        $mediaQuery = MediaFile::where('publication_status', 'published')
            ->where('subject_id', $subjectId)
            ->whereDoesntHave('courses');

        // Apply visibility filters
        $mediaQuery->where(function ($q) use ($classIds) {
            $q->whereNull('class_id');
            if ($classIds->isNotEmpty()) {
                $q->orWhereIn('class_id', $classIds);
            }
        });

        $mediaQuery->where(function ($q) use ($yearGroupIds) {
            $q->whereNull('year_group_id');
            if ($yearGroupIds->isNotEmpty()) {
                $q->orWhereIn('year_group_id', $yearGroupIds);
            }
        });

        $mediaQuery->where(function ($q) use ($studentAcademicYears) {
            $q->whereNull('academic_year');
            if ($studentAcademicYears->isNotEmpty()) {
                $q->orWhereIn('academic_year', $studentAcademicYears);
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

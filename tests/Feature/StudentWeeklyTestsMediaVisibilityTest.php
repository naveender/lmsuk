<?php

use App\Models\User;
use App\Models\Subject;
use App\Models\Paper;
use App\Models\Course;
use App\Models\Week;
use App\Models\YearGroup;
use App\Models\Classes;
use App\Models\MediaFile;
use App\Models\StudentDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('student can view assigned media files that match visibility constraints in weekly tests page', function () {
    // 1. Setup Student
    $student = User::factory()->create([
        'role' => 'student',
        'username' => 'student_weekly_media_' . uniqid()
    ]);

    // Setup YearGroup
    $yearGroup = YearGroup::create([
        'title' => 'Year 5',
        'value' => 'Year 5',
        'is_active' => true,
    ]);

    // Setup Student Details
    $student->studentDetail()->create([
        'group_year' => 'Year 5',
        'academic_year' => '2025-2026',
        'date_of_birth' => '2015-05-05',
    ]);

    // Setup Class
    $class = Classes::create([
        'name' => 'Maths Class A',
        'group_year' => 'Year 5',
        'academic_year' => '2025-2026',
        'is_active' => true,
    ]);
    $student->classes()->attach($class->id);

    // Setup Course and Week
    $course = Course::create([
        'name' => 'Advanced Maths Course',
        'is_active' => true,
    ]);

    $week = Week::create([
        'name' => 'Week 1 - Geometry',
        'course_id' => $course->id,
        'due_date' => now()->addDays(7)->format('Y-m-d'),
    ]);

    // Setup Subject and Paper
    $subject = Subject::create([
        'title' => 'Geometry',
        'is_active' => true,
    ]);

    $paper = Paper::create([
        'title' => 'Geometry Quiz 1',
        'type' => 'homework',
        'subject_id' => $subject->id,
    ]);
    $paper->assignments()->create([
        'assign_type' => 'students',
        'assign_mode' => 'all',
    ]);

    // Link paper to course weekly timeline
    \Illuminate\Support\Facades\DB::table('course_paper')->insert([
        'course_id' => $course->id,
        'paper_id' => $paper->id,
        'week_id' => $week->id,
        'week' => 1,
    ]);

    // 2. Setup Media Files (Videos)
    // Media 1: Visible (matches student's class, year group, academic year and assigned to course/week)
    $visibleMedia = MediaFile::create([
        'title' => 'Visible Geometry Video',
        'description' => 'Visible lecture description',
        'type' => 'youtube',
        'path' => 'https://www.youtube.com/watch?v=12345678901',
        'subject_id' => $subject->id,
        'class_id' => $class->id,
        'year_group_id' => $yearGroup->id,
        'academic_year' => '2025-2026',
        'publication_status' => 'published',
        'status' => 'completed',
    ]);
    $visibleMedia->courses()->attach($course->id, ['week_id' => $week->id, 'week' => 1]);

    // Media 2: Invisible (wrong class)
    $wrongClass = Classes::create([
        'name' => 'English Class B',
        'group_year' => 'Year 5',
        'academic_year' => '2025-2026',
        'is_active' => true,
    ]);
    $invisibleMedia1 = MediaFile::create([
        'title' => 'Wrong Class Video',
        'description' => 'Invisible description',
        'type' => 'youtube',
        'path' => 'https://www.youtube.com/watch?v=12345678902',
        'subject_id' => $subject->id,
        'class_id' => $wrongClass->id,
        'year_group_id' => $yearGroup->id,
        'academic_year' => '2025-2026',
        'publication_status' => 'published',
        'status' => 'completed',
    ]);
    $invisibleMedia1->courses()->attach($course->id, ['week_id' => $week->id, 'week' => 1]);

    // Media 3: Invisible (draft status)
    $invisibleMedia2 = MediaFile::create([
        'title' => 'Draft Video',
        'description' => 'Invisible description',
        'type' => 'youtube',
        'path' => 'https://www.youtube.com/watch?v=12345678903',
        'subject_id' => $subject->id,
        'class_id' => $class->id,
        'year_group_id' => $yearGroup->id,
        'academic_year' => '2025-2026',
        'publication_status' => 'draft',
        'status' => 'completed',
    ]);
    $invisibleMedia2->courses()->attach($course->id, ['week_id' => $week->id, 'week' => 1]);

    // 3. Request weekly tests page
    $response = $this->actingAs($student)
        ->get(route('student.weeklytests', [
            'course_id' => $course->id,
            'week' => $week->id
        ]));

    // 4. Assertions
    $response->assertStatus(200);
    $response->assertViewHas('mediaFiles');

    $mediaFiles = $response->viewData('mediaFiles');
    expect($mediaFiles)->toHaveCount(1);
    expect($mediaFiles->first()->id)->toEqual($visibleMedia->id);

    $response->assertSee('Visible Geometry Video');
    $response->assertDontSee('Wrong Class Video');
    $response->assertDontSee('Draft Video');
});

test('student can view videos when academic year is stored as ID in studentDetail and string in media file', function () {
    $academicYear = \App\Models\AcademicYear::firstOrCreate(
        ['name' => '2026-2027'],
        ['is_active' => true]
    );

    $student = User::factory()->create([
        'role' => 'student',
        'username' => 'student_ay_id_' . uniqid()
    ]);

    $yearGroup = YearGroup::firstOrCreate(
        ['value' => 'Year 6'],
        ['title' => 'Year 6', 'is_active' => true]
    );

    $class = Classes::create([
        'name' => 'Year 6 Class Gold',
        'group_year' => 'Year 6',
        'academic_year' => '2026-2027',
        'is_active' => true,
    ]);
    $student->classes()->attach($class->id);

    // Notice academic_year is stored as ID (matching real admin panel creation)
    $student->studentDetail()->create([
        'group_year' => 'Year 6',
        'academic_year' => (string)$academicYear->id,
        'date_of_birth' => '2014-03-03',
    ]);

    $course = Course::create([
        'name' => 'Science Course',
        'is_active' => true,
    ]);

    $week = Week::create([
        'name' => 'Week 1 - Chemistry',
        'course_id' => $course->id,
        'due_date' => now()->addDays(7)->format('Y-m-d'),
    ]);

    $subject = Subject::create([
        'title' => 'Chemistry',
        'is_active' => true,
    ]);

    // Media file stores academic_year as string name ('2026-2027')
    $media = MediaFile::create([
        'title' => 'Chemistry Video With AY Name',
        'type' => 'youtube',
        'path' => 'https://www.youtube.com/watch?v=chemistry123',
        'subject_id' => $subject->id,
        'class_id' => $class->id,
        'year_group_id' => $yearGroup->id,
        'academic_year' => '2026-2027',
        'publication_status' => 'published',
        'status' => 'completed',
    ]);
    $media->courses()->attach($course->id, ['week_id' => $week->id, 'week' => 1]);

    $response = $this->actingAs($student)
        ->get(route('student.weeklytests', [
            'course_id' => $course->id,
            'week' => $week->id
        ]));

    $response->assertStatus(200);
    $mediaFiles = $response->viewData('mediaFiles');
    expect($mediaFiles->pluck('id')->contains($media->id))->toBeTrue();
    $response->assertSee('Chemistry Video With AY Name');
});

test('student inherits year group and academic year from assigned class for video visibility', function () {
    $academicYear = \App\Models\AcademicYear::firstOrCreate(
        ['name' => '2026-2027'],
        ['is_active' => true]
    );

    $yearGroup = YearGroup::firstOrCreate(
        ['value' => 'Year 7'],
        ['title' => 'Year 7', 'is_active' => true]
    );

    $class = Classes::create([
        'name' => 'Year 7 Blue',
        'group_year' => 'Year 7',
        'academic_year' => '2026-2027',
        'is_active' => true,
    ]);

    $student = User::factory()->create([
        'role' => 'student',
        'username' => 'student_class_inherit_' . uniqid()
    ]);
    $student->classes()->attach($class->id);

    // Student detail has empty group_year and academic_year; student relies on assigned class
    $student->studentDetail()->create([
        'date_of_birth' => '2013-02-02',
    ]);

    $course = Course::create([
        'name' => 'Biology Course',
        'is_active' => true,
    ]);

    $week = Week::create([
        'name' => 'Week 1 - Cell Biology',
        'course_id' => $course->id,
        'due_date' => now()->addDays(7)->format('Y-m-d'),
    ]);

    $subject = Subject::create([
        'title' => 'Biology',
        'is_active' => true,
    ]);

    $media = MediaFile::create([
        'title' => 'Cell Biology Lecture',
        'type' => 'youtube',
        'path' => 'https://www.youtube.com/watch?v=cell1234567',
        'subject_id' => $subject->id,
        'class_id' => $class->id,
        'year_group_id' => $yearGroup->id,
        'academic_year' => '2026-2027',
        'publication_status' => 'published',
        'status' => 'completed',
    ]);
    $media->courses()->attach($course->id, ['week_id' => $week->id, 'week' => 1]);

    $response = $this->actingAs($student)
        ->get(route('student.weeklytests', [
            'course_id' => $course->id,
            'week' => $week->id
        ]));

    $response->assertStatus(200);
    $mediaFiles = $response->viewData('mediaFiles');
    expect($mediaFiles->pluck('id')->contains($media->id))->toBeTrue();
    $response->assertSee('Cell Biology Lecture');
});

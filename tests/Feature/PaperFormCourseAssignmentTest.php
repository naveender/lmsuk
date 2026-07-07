<?php

use App\Models\User;
use App\Models\Classes;
use App\Models\YearGroup;
use App\Models\Subject;
use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Paper;
use App\Livewire\Admin\PaperForm;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can assign paper to existing course in paper form', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);
    $subject = Subject::create(['title' => 'Maths', 'is_active' => true]);
    $class = Classes::create(['name' => 'Class A', 'is_active' => true, 'group_year' => 'Year 1', 'academic_year' => '2026']);
    $yearGroup = YearGroup::create(['title' => 'Year 1', 'value' => '1', 'is_active' => true]);
    $academicYear = AcademicYear::firstOrCreate(['name' => '2026-2027', 'is_active' => true]);
    $course = Course::create(['name' => 'Math Course', 'is_active' => true]);

    Livewire::actingAs($admin)
        ->test(PaperForm::class)
        ->set('title', 'Exam Paper 1')
        ->set('type', 'exam')
        ->set('subject_id', $subject->id)
        ->set('class_id', $class->id)
        ->set('year_group_id', $yearGroup->id)
        ->set('user_id', $admin->id)
        ->set('academic_year', '2026-2027')
        ->set('difficulty', 'medium')
        ->set('total_time', 45)
        ->set('default_marks', 1)
        ->set('course_id', $course->id)
        ->set('week_mode', 'new')
        ->set('new_week_name', 'Week 4')
        ->call('save');

    $paper = Paper::where('title', 'Exam Paper 1')->first();
    expect($paper)->not->toBeNull();
    expect($paper->courses()->where('courses.id', $course->id)->exists())->toBeTrue();
    expect($paper->courses()->first()->pivot->week)->toBe(4);
});

test('admin can instantly create a course and assign paper in paper form', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);
    $subject = Subject::create(['title' => 'Maths', 'is_active' => true]);
    $class = Classes::create(['name' => 'Class A', 'is_active' => true, 'group_year' => 'Year 1', 'academic_year' => '2026']);
    $yearGroup = YearGroup::create(['title' => 'Year 1', 'value' => '1', 'is_active' => true]);
    $academicYear = AcademicYear::firstOrCreate(['name' => '2026-2027', 'is_active' => true]);

    Livewire::actingAs($admin)
        ->test(PaperForm::class)
        ->set('title', 'Exam Paper 2')
        ->set('type', 'exam')
        ->set('subject_id', $subject->id)
        ->set('class_id', $class->id)
        ->set('year_group_id', $yearGroup->id)
        ->set('user_id', $admin->id)
        ->set('academic_year', '2026-2027')
        ->set('difficulty', 'medium')
        ->set('total_time', 45)
        ->set('default_marks', 1)
        ->set('create_new_course', true)
        ->set('new_course_name', 'Instant Prep Course')
        ->set('week_mode', 'new')
        ->set('new_week_name', 'Week 2')
        ->call('save');

    $paper = Paper::where('title', 'Exam Paper 2')->first();
    expect($paper)->not->toBeNull();
    
    $course = Course::where('name', 'Instant Prep Course')->first();
    expect($course)->not->toBeNull();
    
    expect($paper->courses()->where('courses.id', $course->id)->exists())->toBeTrue();
    expect($paper->courses()->first()->pivot->week)->toBe(2);
});

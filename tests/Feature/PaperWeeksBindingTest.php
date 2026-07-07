<?php

use App\Models\User;
use App\Models\Classes;
use App\Models\YearGroup;
use App\Models\Subject;
use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Paper;
use App\Models\Week;
use App\Livewire\Admin\PaperForm;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('quiz paper type validates and saves correctly', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);
    $subject = Subject::create(['title' => 'Maths', 'is_active' => true]);
    $class = Classes::create(['name' => 'Class A', 'is_active' => true, 'group_year' => 'Year 1', 'academic_year' => '2026']);
    $yearGroup = YearGroup::create(['title' => 'Year 1', 'value' => '1', 'is_active' => true]);
    $academicYear = AcademicYear::firstOrCreate(['name' => '2026-2027', 'is_active' => true]);

    Livewire::actingAs($admin)
        ->test(PaperForm::class)
        ->set('title', 'Weekly Quiz Challenge')
        ->set('type', 'quiz') // test type 'quiz'
        ->set('subject_id', $subject->id)
        ->set('class_id', $class->id)
        ->set('year_group_id', $yearGroup->id)
        ->set('user_id', $admin->id)
        ->set('academic_year', '2026-2027')
        ->set('difficulty', 'medium')
        ->set('total_time', 20)
        ->set('default_marks', 1)
        ->call('save');

    $paper = Paper::where('title', 'Weekly Quiz Challenge')->first();
    expect($paper)->not->toBeNull();
    expect($paper->type)->toBe('quiz');
});

test('can create a new week with due date in paper form', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);
    $subject = Subject::create(['title' => 'Maths', 'is_active' => true]);
    $class = Classes::create(['name' => 'Class A', 'is_active' => true, 'group_year' => 'Year 1', 'academic_year' => '2026']);
    $yearGroup = YearGroup::create(['title' => 'Year 1', 'value' => '1', 'is_active' => true]);
    $academicYear = AcademicYear::firstOrCreate(['name' => '2026-2027', 'is_active' => true]);
    $course = Course::create(['name' => 'YS Maths', 'is_active' => true]);

    Livewire::actingAs($admin)
        ->test(PaperForm::class)
        ->set('title', 'Rational Numbers Test')
        ->set('type', 'test')
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
        ->set('new_week_name', 'Week 21 - Rational Numbers')
        ->set('new_week_due_date', '2026-07-20')
        ->call('save');

    $paper = Paper::where('title', 'Rational Numbers Test')->first();
    expect($paper)->not->toBeNull();
    
    // Check week creation
    $week = Week::where('course_id', $course->id)->where('name', 'Week 21 - Rational Numbers')->first();
    expect($week)->not->toBeNull();
    expect($week->due_date->format('Y-m-d'))->toBe('2026-07-20');

    // Check relationship
    expect($paper->courses()->where('courses.id', $course->id)->exists())->toBeTrue();
    $pivot = $paper->courses()->first()->pivot;
    expect($pivot->week_id)->toBe($week->id);
    expect($pivot->week)->toBe(21); // Extracted numeric week
});

test('can select existing week in paper form', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);
    $subject = Subject::create(['title' => 'Maths', 'is_active' => true]);
    $class = Classes::create(['name' => 'Class A', 'is_active' => true, 'group_year' => 'Year 1', 'academic_year' => '2026']);
    $yearGroup = YearGroup::create(['title' => 'Year 1', 'value' => '1', 'is_active' => true]);
    $academicYear = AcademicYear::firstOrCreate(['name' => '2026-2027', 'is_active' => true]);
    $course = Course::create(['name' => 'YS Maths', 'is_active' => true]);
    
    $week = Week::create([
        'course_id' => $course->id,
        'name' => 'Week 21 - Rational Numbers',
        'due_date' => '2026-07-20'
    ]);

    Livewire::actingAs($admin)
        ->test(PaperForm::class)
        ->set('title', 'Second Practice Test')
        ->set('type', 'test')
        ->set('subject_id', $subject->id)
        ->set('class_id', $class->id)
        ->set('year_group_id', $yearGroup->id)
        ->set('user_id', $admin->id)
        ->set('academic_year', '2026-2027')
        ->set('difficulty', 'medium')
        ->set('total_time', 45)
        ->set('default_marks', 1)
        ->set('course_id', $course->id)
        ->set('week_mode', 'existing')
        ->set('selected_week_id', $week->id)
        ->call('save');

    $paper = Paper::where('title', 'Second Practice Test')->first();
    expect($paper)->not->toBeNull();
    
    $pivot = $paper->courses()->first()->pivot;
    expect($pivot->week_id)->toBe($week->id);
    expect($pivot->week)->toBe(21); // Extracted numeric week
});

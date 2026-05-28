<?php

use App\Models\User;
use App\Models\Subject;
use App\Models\Paper;
use App\Models\PaperAttempt;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('student can view assessment overview with paused, finished and last completed statistics', function () {
    // 1. Create student and authenticate
    $student = User::factory()->create([
        'role' => 'student',
        'username' => 'student_test_overview_' . uniqid()
    ]);

    // 2. Create subject
    $subject = Subject::create([
        'title' => 'Maths',
        'is_active' => true,
    ]);

    // 3. Create papers/tests
    $paper1 = Paper::create([
        'title' => 'Maths Test 1',
        'type' => 'test',
        'subject_id' => $subject->id,
    ]);

    $paper2 = Paper::create([
        'title' => 'Maths Test 2',
        'type' => 'test',
        'subject_id' => $subject->id,
    ]);

    $paper3 = Paper::create([
        'title' => 'Maths Test 3',
        'type' => 'test',
        'subject_id' => $subject->id,
    ]);

    // Set up paper assignments so they are visible to student
    // Since Paper::visibleTo queries paper assignments or class/session/etc,
    // let's assign them to "all students" or via a student assignment.
    // Let's check how visibility works in Paper.php
    $paper1->assignments()->create([
        'assign_type' => 'students',
        'assign_mode' => 'all',
    ]);
    $paper2->assignments()->create([
        'assign_type' => 'students',
        'assign_mode' => 'all',
    ]);
    $paper3->assignments()->create([
        'assign_type' => 'students',
        'assign_mode' => 'all',
    ]);

    // 4. Create attempts:
    // - Paper 1 attempt completed
    $completedAt = now()->subDays(2);
    PaperAttempt::create([
        'user_id' => $student->id,
        'paper_id' => $paper1->id,
        'status' => 'completed',
        'time_spent' => 120,
        'completed_at' => $completedAt,
    ]);

    // - Paper 2 attempt paused
    PaperAttempt::create([
        'user_id' => $student->id,
        'paper_id' => $paper2->id,
        'status' => 'paused',
        'time_spent' => 60,
    ]);

    // 5. Send request
    $response = $this->actingAs($student)
        ->get(route('student.assessments'));

    // 6. Assert response
    $response->assertStatus(200);
    $response->assertViewHas('subjects');
    
    $viewSubjects = $response->viewData('subjects');
    expect($viewSubjects)->not->toBeEmpty();
    
    $mathsSubject = $viewSubjects->firstWhere('id', $subject->id);
    expect($mathsSubject)->not->toBeNull();
    
    // Check if the calculated properties are correct
    expect($mathsSubject->total_papers)->toEqual(3);
    expect($mathsSubject->completed_papers_count)->toEqual(1);
    expect($mathsSubject->paused_papers_count)->toEqual(1);
    expect($mathsSubject->last_completed_at->format('Y-m-d H:i:s'))->toEqual($completedAt->format('Y-m-d H:i:s'));

    // Assert that the rendered html contains the stats
    $response->assertSee('Paused');
    $response->assertSee('Finished');
    $response->assertSee('Last Completed');
    $response->assertSee('1/3');
    $response->assertSee($completedAt->format('d/m/Y'));
});

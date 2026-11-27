<?php

use App\Models\User;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Paper;
use App\Models\PaperAttempt;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('student can view subtopics and paginated attempt history with working filters', function () {
    // 1. Create student and authenticate
    $student = User::factory()->create([
        'role' => 'student',
        'username' => 'student_subtopic_test_' . uniqid()
    ]);

    // 2. Create subject
    $subject = Subject::create([
        'title' => 'Science',
        'is_active' => true,
    ]);

    // 3. Create parent topic
    $topic = Topic::create([
        'name' => 'Physics',
        'slug' => 'physics',
        'subject_id' => $subject->id,
    ]);

    // 4. Create subtopics
    $subtopic1 = Topic::create([
        'name' => 'Forces',
        'slug' => 'forces',
        'parent' => $topic->id,
        'subject_id' => $subject->id,
    ]);

    $subtopic2 = Topic::create([
        'name' => 'Energy',
        'slug' => 'energy',
        'parent' => $topic->id,
        'subject_id' => $subject->id,
    ]);

    // 5. Create papers under subtopics
    $paper1 = Paper::create([
        'title' => 'Forces Paper 1',
        'type' => 'test',
        'subject_id' => $subject->id,
        'topic_id' => $topic->id,
        'subtopic_id' => $subtopic1->id,
        'difficulty' => 'easy',
        'total_time' => 30,
    ]);

    $paper2 = Paper::create([
        'title' => 'Energy Paper 1',
        'type' => 'test',
        'subject_id' => $subject->id,
        'topic_id' => $topic->id,
        'subtopic_id' => $subtopic2->id,
        'difficulty' => 'medium',
        'total_time' => 45,
    ]);

    // Assign papers to all students
    $paper1->assignments()->create(['assign_type' => 'students', 'assign_mode' => 'all']);
    $paper2->assignments()->create(['assign_type' => 'students', 'assign_mode' => 'all']);

    // 6. Create attempt history
    // Create 12 attempts to test pagination (pages of 10)
    for ($i = 1; $i <= 12; $i++) {
        PaperAttempt::create([
            'user_id' => $student->id,
            'paper_id' => ($i % 2 === 0) ? $paper1->id : $paper2->id,
            'status' => ($i === 12) ? 'paused' : 'completed',
            'score' => 8,
            'max_score' => 10,
            'time_spent' => 300,
            'created_at' => now()->subHours($i),
        ]);
    }

    // 7. Test basic view
    $response = $this->actingAs($student)
        ->get(route('student.topics.subtopics', $topic->id));

    $response->assertStatus(200);
    $response->assertSee('Forces');
    $response->assertSee('Energy');
    $response->assertSee('Forces Paper 1');
    $response->assertSee('Energy Paper 1');
    $response->assertSee('Attempt History');
    
    // Assert 10 attempts are shown on page 1
    $viewAttempts = $response->viewData('attempts');
    expect($viewAttempts->count())->toEqual(10);
    expect($viewAttempts->total())->toEqual(12);

    // 8. Test filtering attempts by paper name
    $responseFilterPaper = $this->actingAs($student)
        ->get(route('student.topics.subtopics', [
            'topic' => $topic->id,
            'attempt_paper_name' => 'Forces Paper 1'
        ]));

    $responseFilterPaper->assertStatus(200);
    $filterPaperAttempts = $responseFilterPaper->viewData('attempts');
    // Out of 12 attempts, even ones (2, 4, 6, 8, 10, 12) are for paper1 (Forces Paper 1) => 6 attempts
    expect($filterPaperAttempts->total())->toEqual(6);
    foreach ($filterPaperAttempts as $attempt) {
        expect($attempt->paper->title)->toEqual('Forces Paper 1');
    }

    // 9. Test filtering attempts by subtopic id
    $responseFilterSubtopic = $this->actingAs($student)
        ->get(route('student.topics.subtopics', [
            'topic' => $topic->id,
            'attempt_subtopic_id' => $subtopic2->id
        ]));

    $responseFilterSubtopic->assertStatus(200);
    $filterSubtopicAttempts = $responseFilterSubtopic->viewData('attempts');
    // Odd ones are for paper2 (Energy Paper 1, under subtopic2) => 6 attempts (1, 3, 5, 7, 9, 11)
    expect($filterSubtopicAttempts->total())->toEqual(6);
    foreach ($filterSubtopicAttempts as $attempt) {
        expect($attempt->paper->subtopic_id)->toEqual($subtopic2->id);
    }

    // 10. Test filtering attempts by status
    $responseFilterStatus = $this->actingAs($student)
        ->get(route('student.topics.subtopics', [
            'topic' => $topic->id,
            'attempt_status' => 'paused'
        ]));

    $responseFilterStatus->assertStatus(200);
    $filterStatusAttempts = $responseFilterStatus->viewData('attempts');
    // Only 1 attempt is paused
    expect($filterStatusAttempts->total())->toEqual(1);
    expect($filterStatusAttempts->first()->status)->toEqual('paused');
});

test('student subtopics page renders papers in table format with status, difficulty, sort and subtopic filters', function () {
    $student = User::factory()->create([
        'role' => 'student',
        'username' => 'student_subtopic_table_' . uniqid(),
    ]);

    $subject = Subject::create([
        'title' => 'Maths',
        'is_active' => true,
    ]);

    $topic = Topic::create([
        'name' => 'Algebra',
        'slug' => 'algebra',
        'subject_id' => $subject->id,
    ]);

    $subEquations = Topic::create([
        'name' => 'Linear Equations',
        'slug' => 'linear-equations',
        'parent' => $topic->id,
        'subject_id' => $subject->id,
    ]);

    $subSequences = Topic::create([
        'name' => 'Number Sequences',
        'slug' => 'number-sequences',
        'parent' => $topic->id,
        'subject_id' => $subject->id,
    ]);

    $subEmpty = Topic::create([
        'name' => 'Inequalities',
        'slug' => 'inequalities',
        'parent' => $topic->id,
        'subject_id' => $subject->id,
    ]);

    $paperEq = Paper::create([
        'title' => 'Equations Practice Set A',
        'type' => 'test',
        'subject_id' => $subject->id,
        'topic_id' => $topic->id,
        'subtopic_id' => $subEquations->id,
        'difficulty' => 'easy',
        'total_time' => 25,
    ]);

    $paperSeq = Paper::create([
        'title' => 'Sequences Challenge Set B',
        'type' => 'test',
        'subject_id' => $subject->id,
        'topic_id' => $topic->id,
        'subtopic_id' => $subSequences->id,
        'difficulty' => 'hard',
        'total_time' => 40,
    ]);

    $paperEq->assignments()->create(['assign_type' => 'students', 'assign_mode' => 'all']);
    $paperSeq->assignments()->create(['assign_type' => 'students', 'assign_mode' => 'all']);

    PaperAttempt::create([
        'user_id' => $student->id,
        'paper_id' => $paperEq->id,
        'status' => 'completed',
        'score' => 9,
        'max_score' => 10,
        'time_spent' => 420,
        'completed_at' => now()->subDay(),
    ]);

    $response = $this->actingAs($student)
        ->get(route('student.topics.subtopics', $topic->id));

    $response->assertStatus(200)
        ->assertSee('Subtopics & Practice Papers')
        ->assertSee('st-papers-table', false)
        ->assertSee('Questions & Time')
        ->assertSee('Status & Score')
        ->assertSee('Linear Equations')
        ->assertSee('Equations Practice Set A')
        ->assertSee('Number Sequences')
        ->assertSee('Sequences Challenge Set B')
        ->assertSee('Inequalities')
        ->assertSee('No practice papers available yet')
        ->assertSee('Best 90%');

    // Filter by completed status should only return completed paper row
    $completedOnly = $this->actingAs($student)
        ->get(route('student.topics.subtopics', [
            'topic' => $topic->id,
            'paper_status' => 'completed',
        ]));

    $completedOnly->assertStatus(200);
    $rows = $completedOnly->viewData('paperRows');
    expect($rows->total())->toEqual(1);
    expect($rows->first()->paper_title)->toEqual('Equations Practice Set A');

    // Filter by difficulty=hard should only return hard paper row
    $hardOnly = $this->actingAs($student)
        ->get(route('student.topics.subtopics', [
            'topic' => $topic->id,
            'paper_difficulty' => 'hard',
        ]));

    $hardOnly->assertStatus(200);
    $hardRows = $hardOnly->viewData('paperRows');
    expect($hardRows->total())->toEqual(1);
    expect($hardRows->first()->paper_title)->toEqual('Sequences Challenge Set B');
});

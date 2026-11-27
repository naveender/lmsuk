<?php

use App\Models\Paper;
use App\Models\PaperAttempt;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('student can view subject topics with kpi metrics, progress, difficulty counts and accuracy', function () {
    $student = User::factory()->create([
        'role'     => 'student',
        'username' => 'student_topics_test_' . uniqid(),
    ]);

    $maths = Subject::create([
        'title'       => 'Maths',
        'description' => 'Arithmetic, algebra, and geometry practice.',
        'is_active'   => true,
    ]);

    $algebra = Topic::create([
        'name'       => 'Algebra',
        'slug'       => 'algebra',
        'subject_id' => $maths->id,
    ]);

    Topic::create([
        'name'       => 'Linear Equations',
        'slug'       => 'linear-equations',
        'parent'     => $algebra->id,
        'subject_id' => $maths->id,
    ]);

    $geometry = Topic::create([
        'name'       => 'Geometry',
        'slug'       => 'geometry',
        'subject_id' => $maths->id,
    ]);

    $p1 = Paper::create([
        'title'      => 'Algebra Easy 1',
        'type'       => 'test',
        'subject_id' => $maths->id,
        'topic_id'   => $algebra->id,
        'difficulty' => 'easy',
    ]);
    $p2 = Paper::create([
        'title'      => 'Algebra Medium 1',
        'type'       => 'test',
        'subject_id' => $maths->id,
        'topic_id'   => $algebra->id,
        'difficulty' => 'medium',
    ]);
    $p3 = Paper::create([
        'title'      => 'Algebra Hard 1',
        'type'       => 'test',
        'subject_id' => $maths->id,
        'topic_id'   => $algebra->id,
        'difficulty' => 'hard',
    ]);
    $p4 = Paper::create([
        'title'      => 'Geometry Easy 1',
        'type'       => 'test',
        'subject_id' => $maths->id,
        'topic_id'   => $geometry->id,
        'difficulty' => 'easy',
    ]);

    foreach ([$p1, $p2, $p3, $p4] as $paper) {
        $paper->assignments()->create([
            'assign_type' => 'students',
            'assign_mode' => 'all',
        ]);
    }

    PaperAttempt::create([
        'user_id'      => $student->id,
        'paper_id'     => $p1->id,
        'status'       => 'completed',
        'score'        => 8,
        'max_score'    => 10,
        'completed_at' => now()->subDay(),
    ]);

    PaperAttempt::create([
        'user_id'  => $student->id,
        'paper_id' => $p2->id,
        'status'   => 'paused',
    ]);

    $response = $this->actingAs($student)
        ->get(route('student.assessments.topics', $maths->id));

    $response->assertStatus(200);
    $response->assertViewHas('totalTopicsCount', 2);
    $response->assertViewHas('totalTestsCount', 4);
    $response->assertViewHas('completedTestsCount', 1);
    $response->assertViewHas('subjectCompletionPct', 25);
    $response->assertViewHas('subjectAvgAccuracy', 80);

    $response->assertSee('Maths Topics');
    $response->assertSee('Algebra');
    $response->assertSee('Geometry');
    $response->assertSee('1 Subtopic');
    $response->assertSee('1/3 tests');
    $response->assertSee('Avg 80%');
    $response->assertSee('In Progress');
    $response->assertSee('Not Started');
    $response->assertSee('Go To Tests');
});

test('student can filter and sort subject topics by name, status, and test count', function () {
    $student = User::factory()->create([
        'role'     => 'student',
        'username' => 'student_topics_filter_' . uniqid(),
    ]);

    $english = Subject::create([
        'title'     => 'English',
        'is_active' => true,
    ]);

    $comp = Topic::create([
        'name'       => 'Comprehension',
        'slug'       => 'comprehension',
        'subject_id' => $english->id,
    ]);

    $grammar = Topic::create([
        'name'       => 'Grammar',
        'slug'       => 'grammar',
        'subject_id' => $english->id,
    ]);

    $p1 = Paper::create([
        'title'      => 'Comprehension Paper 1',
        'type'       => 'test',
        'subject_id' => $english->id,
        'topic_id'   => $comp->id,
        'difficulty' => 'easy',
    ]);
    $p1->assignments()->create(['assign_type' => 'students', 'assign_mode' => 'all']);

    PaperAttempt::create([
        'user_id'      => $student->id,
        'paper_id'     => $p1->id,
        'status'       => 'completed',
        'score'        => 9,
        'max_score'    => 10,
        'completed_at' => now(),
    ]);

    // Filter by status=completed -> only Comprehension
    $completedResp = $this->actingAs($student)
        ->get(route('student.assessments.topics', [$english->id, 'status' => 'completed']));

    $completedResp->assertStatus(200);
    $completedResp->assertSee('Comprehension');
    expect($completedResp->viewData('topics')->total())->toEqual(1);

    // Filter by topic_name=Gram -> only Grammar
    $searchResp = $this->actingAs($student)
        ->get(route('student.assessments.topics', [$english->id, 'topic_name' => 'Gram']));

    $searchResp->assertStatus(200);
    $searchResp->assertSee('Grammar');
    expect($searchResp->viewData('topics')->total())->toEqual(1);

    // Sort by name_desc -> Grammar comes before Comprehension
    $sortResp = $this->actingAs($student)
        ->get(route('student.assessments.topics', [$english->id, 'sort' => 'name_desc']));

    $sortResp->assertStatus(200);
    $items = $sortResp->viewData('topics')->items();
    expect($items[0]->name)->toEqual('Grammar');
    expect($items[1]->name)->toEqual('Comprehension');
});

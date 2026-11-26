<?php

use App\Models\Paper;
use App\Models\PaperAttempt;
use App\Models\Question;
use App\Models\StudentAnswer;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('student with no attempts sees focus areas layout with sample preview', function () {
    $student = User::factory()->create([
        'role'     => 'student',
        'username' => 'student_fa_empty_' . uniqid(),
    ]);

    $response = $this->actingAs($student)
        ->get(route('student.focusareas'));

    $response->assertStatus(200);
    $response->assertSee('Focus Areas');
    $response->assertSee('Identify your strengths and areas to improve.');
    $response->assertSee('Strong Areas');
    $response->assertSee('Developing Areas');
    $response->assertSee('Areas to Improve');
    $response->assertSee('Total Topics');
    $response->assertSee('Topic Performance');
    $response->assertSee('Subject Overview');
    $response->assertSee('Keep Going!');
    $response->assertSee('Sample Preview');
    $response->assertViewHas('isSamplePreview', true);
    $response->assertViewHas('totalTopicsCount', 12);
});

test('student with completed attempts sees real topic tiers, subject averages, and period filtering', function () {
    $student = User::factory()->create([
        'role'     => 'student',
        'username' => 'student_fa_real_' . uniqid(),
    ]);

    $english = Subject::create(['title' => 'English', 'is_active' => true]);
    $maths = Subject::create(['title' => 'Maths', 'is_active' => true]);
    $vr = Subject::create(['title' => 'Verbal Reasoning', 'is_active' => true]);

    $compTopic = Topic::create(['name' => 'Comprehension', 'slug' => 'comprehension', 'subject_id' => $english->id]);
    $algebraTopic = Topic::create(['name' => 'Algebra', 'slug' => 'algebra', 'subject_id' => $maths->id]);
    $codesTopic = Topic::create(['name' => 'Codes & Patterns', 'slug' => 'codes-patterns', 'subject_id' => $vr->id]);

    $engPaper = Paper::create([
        'title'      => 'Comprehension Paper 1',
        'type'       => 'test',
        'subject_id' => $english->id,
        'topic_id'   => $compTopic->id,
    ]);
    $mathPaper = Paper::create([
        'title'      => 'Algebra Paper 1',
        'type'       => 'test',
        'subject_id' => $maths->id,
        'topic_id'   => $algebraTopic->id,
    ]);
    $vrPaper = Paper::create([
        'title'      => 'Codes Paper 1',
        'type'       => 'test',
        'subject_id' => $vr->id,
        'topic_id'   => $codesTopic->id,
    ]);

    // Recent Strong attempt (90%)
    PaperAttempt::create([
        'user_id'      => $student->id,
        'paper_id'     => $engPaper->id,
        'status'       => 'completed',
        'score'        => 9,
        'max_score'    => 10,
        'completed_at' => now()->subDays(5),
    ]);

    // Recent Developing attempt (70%)
    PaperAttempt::create([
        'user_id'      => $student->id,
        'paper_id'     => $mathPaper->id,
        'status'       => 'completed',
        'score'        => 7,
        'max_score'    => 10,
        'completed_at' => now()->subDays(10),
    ]);

    // Older Focus attempt (50%, 6 weeks ago — outside 4_weeks, inside 12_weeks)
    PaperAttempt::create([
        'user_id'      => $student->id,
        'paper_id'     => $vrPaper->id,
        'status'       => 'completed',
        'score'        => 5,
        'max_score'    => 10,
        'completed_at' => now()->subWeeks(6),
    ]);

    // Default 12 weeks view includes all 3 attempts
    $response = $this->actingAs($student)->get(route('student.focusareas'));
    $response->assertStatus(200);
    $response->assertViewHas('isSamplePreview', false);
    $response->assertViewHas('strongCount', 1);
    $response->assertViewHas('developingCount', 1);
    $response->assertViewHas('focusCount', 1);
    $response->assertViewHas('totalTopicsCount', 3);

    $response->assertSee('Comprehension');
    $response->assertSee('90%');
    $response->assertSee('Algebra');
    $response->assertSee('70%');
    $response->assertSee('Codes &amp; Patterns', false);
    $response->assertSee('50%');

    // Explicit 4_weeks filter excludes the 6-week-old VR attempt
    $fourWeeksResp = $this->actingAs($student)->get(route('student.focusareas', ['period' => '4_weeks']));
    $fourWeeksResp->assertStatus(200);
    $fourWeeksResp->assertViewHas('strongCount', 1);
    $fourWeeksResp->assertViewHas('developingCount', 1);
    $fourWeeksResp->assertViewHas('focusCount', 0);
    $fourWeeksResp->assertViewHas('totalTopicsCount', 2);
});

test('focus areas aggregates question-level topics when paper has no topic_id', function () {
    $student = User::factory()->create([
        'role'     => 'student',
        'username' => 'student_fa_qlevel_' . uniqid(),
    ]);

    $maths = Subject::create(['title' => 'Maths', 'is_active' => true]);
    $geometryTopic = Topic::create(['name' => 'Shapes & Geometry', 'slug' => 'shapes-geometry', 'subject_id' => $maths->id]);

    $mixedPaper = Paper::create([
        'title'      => 'Weekly Mock Paper',
        'type'       => 'test',
        'subject_id' => $maths->id,
        'topic_id'   => null,
    ]);

    $q1 = Question::create([
        'title'      => 'Angle Question',
        'type'       => 'single_choice_radio',
        'subject_id' => $maths->id,
        'topic_id'   => $geometryTopic->id,
        'marks'      => 2,
    ]);

    $q2 = Question::create([
        'title'      => 'Perimeter Question',
        'type'       => 'single_choice_radio',
        'subject_id' => $maths->id,
        'topic_id'   => $geometryTopic->id,
        'marks'      => 2,
    ]);

    $attempt = PaperAttempt::create([
        'user_id'      => $student->id,
        'paper_id'     => $mixedPaper->id,
        'status'       => 'completed',
        'score'        => 2,
        'max_score'    => 4,
        'completed_at' => now()->subDay(),
    ]);

    StudentAnswer::create([
        'paper_attempt_id' => $attempt->id,
        'question_id'      => $q1->id,
        'is_correct'       => true,
        'marks_obtained'   => 2,
    ]);

    StudentAnswer::create([
        'paper_attempt_id' => $attempt->id,
        'question_id'      => $q2->id,
        'is_correct'       => false,
        'marks_obtained'   => 0,
    ]);

    $response = $this->actingAs($student)->get(route('student.focusareas'));
    $response->assertStatus(200);
    $response->assertViewHas('isSamplePreview', false);
    $response->assertViewHas('focusCount', 1);
    $response->assertSee('Shapes &amp; Geometry', false);
    $response->assertSee('50%');
});

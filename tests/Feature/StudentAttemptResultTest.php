<?php

use App\Models\Paper;
use App\Models\PaperAttempt;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\StudentAnswer;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('student can view modernized test result page with question review and topic breakdown', function () {
    $student = User::factory()->create([
        'role'     => 'student',
        'username' => 'student_result_ok_' . uniqid(),
    ]);

    $maths = Subject::create(['title' => 'Maths', 'is_active' => true]);
    $algebra = Topic::create(['name' => 'Algebra', 'slug' => 'algebra', 'subject_id' => $maths->id]);
    $geometry = Topic::create(['name' => 'Geometry', 'slug' => 'geometry', 'subject_id' => $maths->id]);

    $paper = Paper::create([
        'title'                              => 'Maths Mixed Assessment',
        'type'                               => 'test',
        'subject_id'                         => $maths->id,
        'topic_id'                           => null, // verifies null topic_id safety
        'display_result_question_by_question' => true,
        'hide_result'                        => false,
        'default_marks'                      => 2,
    ]);

    $q1 = Question::create([
        'title'       => 'Solve x',
        'description' => 'What is 2 + 2?',
        'type'        => 'single_choice_radio',
        'subject_id'  => $maths->id,
        'topic_id'    => $algebra->id,
        'marks'       => 2,
        'explanation' => 'Adding 2 and 2 equals 4.',
    ]);
    $q1Correct = QuestionOption::create(['question_id' => $q1->id, 'option_text' => '4', 'is_correct' => true, 'sort_order' => 1]);
    QuestionOption::create(['question_id' => $q1->id, 'option_text' => '5', 'is_correct' => false, 'sort_order' => 2]);

    $q2 = Question::create([
        'title'       => 'Triangle Angles',
        'description' => 'Sum of angles in a triangle?',
        'type'        => 'single_choice_radio',
        'subject_id'  => $maths->id,
        'topic_id'    => $geometry->id,
        'marks'       => 2,
        'explanation' => 'Angles in a triangle always sum to 180.',
    ]);
    QuestionOption::create(['question_id' => $q2->id, 'option_text' => '180', 'is_correct' => true, 'sort_order' => 1]);
    $q2Wrong = QuestionOption::create(['question_id' => $q2->id, 'option_text' => '360', 'is_correct' => false, 'sort_order' => 2]);

    $q3 = Question::create([
        'title'       => 'Unanswered Question',
        'description' => 'What is 10 / 2?',
        'type'        => 'fill_in_the_blanks',
        'subject_id'  => $maths->id,
        'topic_id'    => $algebra->id,
        'marks'       => 2,
        'metadata'    => ['blank_answers' => ['5']],
    ]);

    $paper->questions()->attach($q1->id, ['sort_order' => 1, 'marks' => 2]);
    $paper->questions()->attach($q2->id, ['sort_order' => 2, 'marks' => 2]);
    $paper->questions()->attach($q3->id, ['sort_order' => 3, 'marks' => 2]);

    $attempt = PaperAttempt::create([
        'user_id'         => $student->id,
        'paper_id'        => $paper->id,
        'status'          => 'completed',
        'score'           => 2,
        'max_score'       => 6,
        'correct_answers' => 1,
        'total_questions' => 3,
        'time_spent'      => 125,
        'completed_at'    => null, // verifies null completed_at safety
    ]);

    StudentAnswer::create([
        'paper_attempt_id'   => $attempt->id,
        'question_id'        => $q1->id,
        'selected_option_id' => $q1Correct->id,
        'is_correct'         => true,
        'marks_obtained'     => 2,
        'time_spent'         => 35,
    ]);

    StudentAnswer::create([
        'paper_attempt_id'   => $attempt->id,
        'question_id'        => $q2->id,
        'selected_option_id' => $q2Wrong->id,
        'is_correct'         => false,
        'marks_obtained'     => 0,
        'time_spent'         => 50,
        'is_flagged'         => true,
    ]);

    $response = $this->actingAs($student)
        ->get(route('student.attempts.result', $attempt->id));

    $response->assertStatus(200);
    $response->assertViewHas('percentage', 33);
    $response->assertViewHas('correctCount', 1);
    $response->assertViewHas('incorrectCount', 1);
    $response->assertViewHas('unansweredCount', 1);
    $response->assertViewHas('formattedTimeSpent', '2m 05s');

    $response->assertSee('Maths Mixed Assessment');
    $response->assertSee('33%');
    $response->assertSee('2 / 6');
    $response->assertSee('Response Breakdown');
    $response->assertSee('Topic Breakdown');
    $response->assertSee('Question-by-Question Review');
    $response->assertSee('Adding 2 and 2 equals 4.');
    $response->assertSee('Navigator');
    $response->assertSee('Flagged');
});

test('unauthorized student cannot view another students attempt result', function () {
    $owner = User::factory()->create(['role' => 'student', 'username' => 'owner_' . uniqid()]);
    $other = User::factory()->create(['role' => 'student', 'username' => 'other_' . uniqid()]);
    $subject = Subject::create(['title' => 'English', 'is_active' => true]);
    $paper = Paper::create(['title' => 'Eng Paper', 'type' => 'test', 'subject_id' => $subject->id]);

    $attempt = PaperAttempt::create([
        'user_id'  => $owner->id,
        'paper_id' => $paper->id,
        'status'   => 'completed',
    ]);

    $this->actingAs($other)
        ->get(route('student.attempts.result', $attempt->id))
        ->assertStatus(403);
});

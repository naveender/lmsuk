<?php

use App\Models\Course;
use App\Models\Paper;
use App\Models\PaperAttempt;
use App\Models\Subject;
use App\Models\User;
use App\Models\Week;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('student weeklytests page displays redesigned ui with subject selector near week switcher, final deadline, and real metrics', function () {
    $student = User::factory()->create([
        'role' => 'student',
        'username' => 'weekly_ui_' . uniqid(),
    ]);

    $maths = Subject::create(['title' => 'Maths', 'is_active' => true]);
    $english = Subject::create(['title' => 'English', 'is_active' => true]);

    $course = Course::create(['name' => 'Year 5 Maths Intensive', 'is_active' => true]);

    $dueDate = now()->addDays(4)->format('Y-m-d');
    $week = Week::create([
        'name' => 'Week 21',
        'course_id' => $course->id,
        'due_date' => $dueDate,
    ]);

    $mathsPaper = Paper::create([
        'title' => 'Coordinates Set A',
        'type' => 'quiz',
        'subject_id' => $maths->id,
        'difficulty' => 'easy',
        'total_time' => 20,
    ]);
    $mathsPaper->assignments()->create(['assign_type' => 'students', 'assign_mode' => 'all']);

    $englishPaper = Paper::create([
        'title' => 'Comprehension Passage B',
        'type' => 'homework',
        'subject_id' => $english->id,
        'difficulty' => 'medium',
        'total_time' => 30,
    ]);
    $englishPaper->assignments()->create(['assign_type' => 'students', 'assign_mode' => 'all']);

    DB::table('course_paper')->insert([
        ['course_id' => $course->id, 'paper_id' => $mathsPaper->id, 'week_id' => $week->id, 'week' => 21],
        ['course_id' => $course->id, 'paper_id' => $englishPaper->id, 'week_id' => $week->id, 'week' => 21],
    ]);

    PaperAttempt::create([
        'user_id' => $student->id,
        'paper_id' => $mathsPaper->id,
        'status' => 'completed',
        'score' => 8,
        'max_score' => 10,
        'time_spent' => 750,
        'completed_at' => now()->subHours(2),
    ]);

    $response = $this->actingAs($student)->get(route('student.weeklytests', [
        'course_id' => $course->id,
        'week' => $week->id,
    ]));

    $expectedDeadline = \Carbon\Carbon::parse($dueDate)->format('d M Y, 21:00');

    $response->assertStatus(200)
        ->assertSee('Year 5 Maths Intensive — Week 21')
        ->assertSee('Final Deadline:')
        ->assertSee($expectedDeadline)
        ->assertSee('id="subject_filter"', false)
        ->assertSee('id="week_filter"', false)
        ->assertSee('id="weekPrevBtn"', false)
        ->assertSee('id="weekNextBtn"', false)
        ->assertSee('1. Core Video Lecture Streams')
        ->assertSee('2. Online Assignment Tasks Evaluation')
        ->assertSee('3. HOMEWORK TASKS')
        ->assertSee('80%')
        ->assertSee('1 / 2')
        ->assertSee('Coordinates Set A')
        ->assertSee('Comprehension Passage B');

    // Subject filter narrows down papers cleanly
    $filtered = $this->actingAs($student)->get(route('student.weeklytests', [
        'course_id' => $course->id,
        'week' => $week->id,
        'subject_id' => $english->id,
    ]));

    $filtered->assertStatus(200)
        ->assertSee('Comprehension Passage B')
        ->assertDontSee('Coordinates Set A')
        ->assertDontSee('94%'); // No fake fallback score when 0 English tests are completed
});

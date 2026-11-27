<?php

use App\Models\MediaFile;
use App\Models\Paper;
use App\Models\PaperAttempt;
use App\Models\StudentVideoProgress;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('student dashboard shows zero progress and empty states without mock data for new student', function () {
    $student = User::factory()->create([
        'role'     => 'student',
        'username' => 'student_dash_empty_' . uniqid(),
    ]);

    Subject::create(['title' => 'English', 'is_active' => true]);
    Subject::create(['title' => 'Maths', 'is_active' => true]);

    $response = $this->actingAs($student)->get(route('student.dashboard'));

    $response->assertStatus(200);
    $response->assertViewHas('overallProgressPct', 0);
    $response->assertViewHas('overallCompletedCount', 0);
    $response->assertViewHas('overallTotalCount', 0);
    $response->assertViewHas('learningStreak', 0);

    // Ensure old hardcoded sample/mock numbers are completely absent
    $response->assertDontSee('5 of 12');
    $response->assertDontSee('02 Sep');
    $response->assertDontSee('3/5');
    $response->assertDontSee('47%');
    $response->assertDontSee('65%');
});

test('student dashboard displays real per-student metrics and isolates other students data', function () {
    $studentA = User::factory()->create([
        'role'     => 'student',
        'username' => 'student_dash_a_' . uniqid(),
    ]);
    $studentB = User::factory()->create([
        'role'     => 'student',
        'username' => 'student_dash_b_' . uniqid(),
    ]);

    $english = Subject::create(['title' => 'English', 'is_active' => true]);
    $maths = Subject::create(['title' => 'Maths', 'is_active' => true]);

    $algebra = Topic::create(['name' => 'Algebra Mastery', 'slug' => 'algebra-mastery', 'subject_id' => $maths->id]);
    $grammar = Topic::create(['name' => 'Grammar Rules', 'slug' => 'grammar-rules', 'subject_id' => $english->id]);

    $mathPaper = Paper::create([
        'title'      => 'Algebra Quiz 1',
        'type'       => 'test',
        'subject_id' => $maths->id,
        'topic_id'   => $algebra->id,
    ]);
    $engPaper = Paper::create([
        'title'      => 'Grammar Quiz 1',
        'type'       => 'test',
        'subject_id' => $english->id,
        'topic_id'   => $grammar->id,
    ]);
    $mathPaper->assignments()->create(['assign_type' => 'students', 'assign_mode' => 'all']);
    $engPaper->assignments()->create(['assign_type' => 'students', 'assign_mode' => 'all']);

    $video = MediaFile::create([
        'title'              => 'Algebra Intro Video',
        'type'               => 'video_url',
        'path'               => 'https://example.com/video.mp4',
        'subject_id'         => $maths->id,
        'publication_status' => 'published',
    ]);

    // Student A completes Math paper (75%) and video today, pauses English paper
    PaperAttempt::create([
        'user_id'      => $studentA->id,
        'paper_id'     => $mathPaper->id,
        'status'       => 'completed',
        'score'        => 3,
        'max_score'    => 4,
        'completed_at' => now(),
    ]);
    PaperAttempt::create([
        'user_id'    => $studentA->id,
        'paper_id'   => $engPaper->id,
        'status'     => 'paused',
        'started_at' => now(),
        'paused_at'  => now(),
    ]);
    StudentVideoProgress::create([
        'user_id'       => $studentA->id,
        'media_file_id' => $video->id,
        'watch_time'    => 120,
        'last_position' => 120,
        'is_completed'  => true,
    ]);

    // Student B completes English paper at 25% (must NOT leak to Student A)
    PaperAttempt::create([
        'user_id'      => $studentB->id,
        'paper_id'     => $engPaper->id,
        'status'       => 'completed',
        'score'        => 1,
        'max_score'    => 4,
        'completed_at' => now(),
    ]);

    $response = $this->actingAs($studentA)->get(route('student.dashboard'));

    $response->assertStatus(200);
    // Student A has 2 visible papers + 1 visible video = 3 tasks; completed math paper + video = 2 of 3 (67%)
    $response->assertViewHas('overallTotalCount', 3);
    $response->assertViewHas('overallCompletedCount', 2);
    $response->assertViewHas('overallProgressPct', 67);
    $response->assertViewHas('learningStreak', 1);

    $response->assertSee('2 of 3');
    $response->assertSee('Algebra Mastery');
    $response->assertSee('75%');
    $response->assertSee('Grammar Quiz 1');
    $response->assertDontSee('Grammar Rules');
    $response->assertDontSee('25%');
});

test('student analytics shows zero and empty states without mock data when student has no attempts', function () {
    $student = User::factory()->create([
        'role'     => 'student',
        'username' => 'student_an_empty_' . uniqid(),
    ]);

    Subject::create(['title' => 'English', 'is_active' => true]);
    Subject::create(['title' => 'Maths', 'is_active' => true]);

    $response = $this->actingAs($student)->get(route('student.analytics'));

    $response->assertStatus(200);
    $response->assertViewHas('totalAssessments', 0);
    $response->assertViewHas('averageScore', 0);
    $response->assertViewHas('scoreGrowth', null);
    $response->assertViewHas('overallProgress', null);
    $response->assertViewHas('streak', 0);
    $response->assertViewHas('hasSubjectData', false);
    $response->assertViewHas('accuracyByTopic', []);

    $response->assertDontSee('+12%');
    $response->assertDontSee('+18%');
    $response->assertDontSee('72%');
    $response->assertDontSee('84%');
    $response->assertSee('No completed assessments found');
});

test('student analytics calculates real kpis, trend, subject scores, and isolates per student', function () {
    $studentA = User::factory()->create([
        'role'     => 'student',
        'username' => 'student_an_a_' . uniqid(),
    ]);
    $studentB = User::factory()->create([
        'role'     => 'student',
        'username' => 'student_an_b_' . uniqid(),
    ]);

    $english = Subject::create(['title' => 'English', 'is_active' => true]);
    $maths = Subject::create(['title' => 'Maths', 'is_active' => true]);

    $comp = Topic::create(['name' => 'Comprehension Real', 'slug' => 'comp-real', 'subject_id' => $english->id]);
    $frac = Topic::create(['name' => 'Fractions Real', 'slug' => 'frac-real', 'subject_id' => $maths->id]);

    $engPaper = Paper::create(['title' => 'Eng Real Paper', 'type' => 'test', 'subject_id' => $english->id, 'topic_id' => $comp->id]);
    $mathPaper1 = Paper::create(['title' => 'Math Real Paper 1', 'type' => 'test', 'subject_id' => $maths->id, 'topic_id' => $frac->id]);
    $mathPaper2 = Paper::create(['title' => 'Math Real Paper 2', 'type' => 'test', 'subject_id' => $maths->id, 'topic_id' => $frac->id]);

    PaperAttempt::create([
        'user_id'      => $studentA->id,
        'paper_id'     => $mathPaper1->id,
        'status'       => 'completed',
        'score'        => 6,
        'max_score'    => 10,
        'completed_at' => now()->subDays(4),
    ]);
    PaperAttempt::create([
        'user_id'      => $studentA->id,
        'paper_id'     => $mathPaper2->id,
        'status'       => 'completed',
        'score'        => 8,
        'max_score'    => 10,
        'completed_at' => now()->subDays(2),
    ]);
    PaperAttempt::create([
        'user_id'      => $studentA->id,
        'paper_id'     => $engPaper->id,
        'status'       => 'completed',
        'score'        => 9,
        'max_score'    => 10,
        'completed_at' => now(),
    ]);

    // Student B attempt — isolated
    PaperAttempt::create([
        'user_id'      => $studentB->id,
        'paper_id'     => $engPaper->id,
        'status'       => 'completed',
        'score'        => 2,
        'max_score'    => 10,
        'completed_at' => now(),
    ]);

    $response = $this->actingAs($studentA)->get(route('student.analytics'));

    $response->assertStatus(200);
    $response->assertViewHas('totalAssessments', 3);
    $response->assertViewHas('averageScore', 77);
    $response->assertViewHas('streak', 1);
    $response->assertViewHas('hasSubjectData', true);

    $trendData = $response->viewData('trendData');
    expect($trendData['series'])->toEqual([60, 80, 90]);

    $subjectPerf = collect($response->viewData('subjectPerformance'))->keyBy('key');
    expect($subjectPerf['english']['score'])->toEqual(90);
    expect($subjectPerf['maths']['score'])->toEqual(70);

    $response->assertSee('Comprehension Real');
    $response->assertSee('Fractions Real');
    $response->assertSee('Math Real Paper 1');
});

test('student analytics provides per-subject analytics snapshots, extended badge tiers, and focus recommendations', function () {
    $student = User::factory()->create([
        'role'     => 'student',
        'username' => 'student_an_enh_' . uniqid(),
    ]);

    $maths = Subject::create(['title' => 'Maths', 'is_active' => true]);
    $nvr = Subject::create(['title' => 'Non-Verbal Reasoning', 'is_active' => true]);

    $geoTopic = Topic::create(['name' => 'Geometry Basics', 'slug' => 'geo-basics', 'subject_id' => $maths->id]);
    $rotTopic = Topic::create(['name' => '3D Rotations', 'slug' => 'rotations-3d', 'subject_id' => $nvr->id]);

    $mathPaper = Paper::create(['title' => 'Geo Practice', 'type' => 'test', 'subject_id' => $maths->id, 'topic_id' => $geoTopic->id]);
    $nvrPaper = Paper::create(['title' => 'NVR Spatial Test', 'type' => 'test', 'subject_id' => $nvr->id, 'topic_id' => $rotTopic->id]);

    PaperAttempt::create([
        'user_id'      => $student->id,
        'paper_id'     => $mathPaper->id,
        'status'       => 'completed',
        'score'        => 9,
        'max_score'    => 10,
        'completed_at' => now()->subDays(2),
    ]);

    PaperAttempt::create([
        'user_id'      => $student->id,
        'paper_id'     => $nvrPaper->id,
        'status'       => 'completed',
        'score'        => 5,
        'max_score'    => 10,
        'completed_at' => now()->subDay(),
    ]);

    $response = $this->actingAs($student)->get(route('student.analytics', ['subject' => 'nvr']));

    $response->assertStatus(200);
    $response->assertViewHas('bestScore', 90);
    $response->assertViewHas('latestScore', 50);
    $response->assertViewHas('activeSubjectFilter', 'nvr');

    $subjectMap = $response->viewData('subjectAnalyticsMap');
    expect($subjectMap['maths']['summary']['average_score'])->toEqual(90);
    expect($subjectMap['nvr']['summary']['average_score'])->toEqual(50);
    expect($subjectMap['nvr']['trend']['series'])->toEqual([50]);

    $lowestTopic = $response->viewData('lowestTopic');
    expect($lowestTopic['name'])->toEqual('3D Rotations');

    $response->assertSee('Recommended Focus: 3D Rotations');
    $response->assertSee('subj-nvr');
    $response->assertSee('NVR Spatial Test');
    $response->assertSee('All Subjects');
});

<?php

use App\Models\User;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('guest or student cannot access import page', function () {
    $student = User::factory()->create(['role' => 'student', 'username' => 'student_test']);

    // Guest redirect to login
    $this->get(route('admin.questions.import-form'))->assertRedirect();

    // Student forbidden / redirect (depending on how middleware acts)
    // The role:admin middleware aborts 403 or redirects. Let's verify actingAs returns 403.
    $this->actingAs($student)
        ->get(route('admin.questions.import-form'))
        ->assertStatus(403);
});

test('admin can access import page', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);

    $response = $this->actingAs($admin)
        ->get(route('admin.questions.import-form'));

    $response->assertStatus(200);
    $response->assertSee('Import Questions');
});

test('admin can download sample CSV', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);

    $response = $this->actingAs($admin)
        ->get(route('admin.questions.import-sample'));

    $response->assertStatus(200);
    $response->assertHeader('Content-Disposition', 'attachment; filename=questions_import_sample.csv');
    
    $content = $response->streamedContent();
    expect($content)->toContain('title,description,type,subject');
    expect($content)->toContain('Identify the synonym of the word \'diligent\'.');
});

test('admin can parse valid CSV file', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);

    // Create a dummy CSV file content
    $csvContent = "title,description,type,subject,topic,subtopic,difficulty,marks,explanation,option_1,option_2,option_3,option_4,option_5,correct_options\n";
    $csvContent .= "Test Question 1,Desc,single_choice_radio,Math,Calculus,,easy,1,explain,optA,optB,,,2\n";
    
    $file = UploadedFile::fake()->createWithContent('questions.csv', $csvContent);

    $response = $this->actingAs($admin)
        ->postJson(route('admin.questions.import-parse'), [
            'file' => $file
        ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'success',
        'import_token',
        'total_rows',
        'headers'
    ]);
    
    $data = $response->json();
    expect($data['success'])->toBeTrue();
    expect($data['total_rows'])->toBe(1);
    
    // Clean up temporary file
    $tempPath = storage_path('app/temp_imports/import_' . $data['import_token'] . '.csv');
    if (file_exists($tempPath)) {
        unlink($tempPath);
    }
});

test('admin can process import chunks and auto create relations', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);

    // 1. Prepare CSV Content
    $headers = [
        'title', 'description', 'type', 'subject', 'topic', 'subtopic', 'difficulty', 'marks', 'explanation',
        'option_1', 'option_2', 'option_3', 'option_4', 'option_5', 'correct_options',
        'blank_answers', 'match_left_1', 'match_right_1', 'match_left_2', 'match_right_2',
        'match_left_3', 'match_right_3', 'match_left_4', 'match_right_4',
        'word_limit', 'allowed_file_types', 'max_file_size'
    ];

    $rows = [
        [
            'title' => 'Calculus Limit Question',
            'description' => 'What is limit?',
            'type' => 'single_choice_radio',
            'subject' => 'Math',
            'topic' => 'Calculus',
            'subtopic' => 'Limits',
            'difficulty' => 'easy',
            'marks' => 2,
            'explanation' => 'Simple explanation',
            'option_1' => 'Option A',
            'option_2' => 'Option B',
            'option_3' => 'Option C',
            'option_4' => 'Option D',
            'correct_options' => '1',
        ],
        [
            'title' => 'The formula of water is [blank] and carbon dioxide is [blank]',
            'type' => 'fill_in_the_blanks',
            'subject' => 'Math',
            'topic' => 'Calculus',
            'subtopic' => 'Limits',
            'difficulty' => 'medium',
            'marks' => 1,
            'blank_answers' => 'H2O|CO2',
        ],
        [
            'title' => 'Matching Elements',
            'type' => 'matching_text',
            'subject' => 'Chemistry',
            'topic' => 'Periodic',
            'subtopic' => 'Bonds',
            'difficulty' => 'hard',
            'marks' => 5,
            'match_left_1' => 'Left A',
            'match_right_1' => 'Right A',
            'match_left_2' => 'Left B',
            'match_right_2' => 'Right B',
        ],
        [
            'title' => 'Essay Question',
            'type' => 'free_text',
            'subject' => 'English',
            'topic' => 'Grammar',
            'difficulty' => 'easy',
            'marks' => 1,
            'word_limit' => 500,
        ],
    ];

    $handle = fopen('php://temp', 'r+');
    fputcsv($handle, $headers);
    foreach ($rows as $row) {
        $data = [];
        foreach ($headers as $header) {
            $data[] = $row[$header] ?? '';
        }
        fputcsv($handle, $data);
    }
    rewind($handle);
    $csvContent = stream_get_contents($handle);
    fclose($handle);

    $token = \Illuminate\Support\Str::random(32);
    $tempDir = storage_path('app/temp_imports');
    if (!file_exists($tempDir)) {
        mkdir($tempDir, 0755, true);
    }
    file_put_contents($tempDir . '/import_' . $token . '.csv', $csvContent);

    // 2. Process first chunk (offset 0, limit 2)
    $response = $this->actingAs($admin)
        ->postJson(route('admin.questions.import-process'), [
            'import_token' => $token,
            'offset' => 0,
            'limit' => 2
        ]);

    $response->assertStatus(200);
    $data = $response->json();
    
    expect($data['success'])->toBeTrue();
    expect($data['results']['success_count'])->toBe(2);
    expect($data['results']['failed_count'])->toBe(0);
    expect($data['results']['completed'])->toBeFalse();

    // Verify database changes
    $subject = Subject::where('title', 'Math')->first();
    expect($subject)->not->toBeNull();
    
    $topic = Topic::where('name', 'Calculus')->where('subject_id', $subject->id)->first();
    expect($topic)->not->toBeNull();

    $subtopic = Topic::where('name', 'Limits')->where('parent', $topic->id)->first();
    expect($subtopic)->not->toBeNull();

    $q1 = Question::where('title', 'Calculus Limit Question')->first();
    expect($q1)->not->toBeNull();
    expect($q1->type)->toBe('single_choice_radio');
    expect($q1->difficulty)->toBe('easy');
    expect($q1->marks)->toBe(2);
    expect($q1->options)->toHaveCount(4);
    expect($q1->options[0]->option_text)->toBe('Option A');
    expect($q1->options[0]->is_correct)->toBeTrue();
    expect($q1->options[1]->is_correct)->toBeFalse();

    $q2 = Question::where('title', 'The formula of water is ___ and carbon dioxide is ___')->first();
    expect($q2)->not->toBeNull();
    expect($q2->type)->toBe('fill_in_the_blanks');
    expect($q2->description)->toBe('The formula of water is ___ and carbon dioxide is ___');
    expect($q2->metadata['blank_answers'])->toBe(['H2O', 'CO2']);

    // 3. Process second chunk (offset 2, limit 2)
    $response2 = $this->actingAs($admin)
        ->postJson(route('admin.questions.import-process'), [
            'import_token' => $token,
            'offset' => 2,
            'limit' => 2
        ]);

    $response2->assertStatus(200);
    $data2 = $response2->json();

    expect($data2['success'])->toBeTrue();
    expect($data2['results']['success_count'])->toBe(2);
    expect($data2['results']['completed'])->toBeTrue(); // All 4 rows processed

    // Verify second chunk
    $q3 = Question::where('title', 'Matching Elements')->first();
    expect($q3)->not->toBeNull();
    expect($q3->metadata['matching_pairs'])->toHaveCount(2);
    expect($q3->metadata['matching_pairs'][0])->toBe(['left' => 'Left A', 'right' => 'Right A']);

    $q4 = Question::where('title', 'Essay Question')->first();
    expect($q4)->not->toBeNull();
    expect($q4->metadata['word_limit'])->toBe(500);

    // Verify temp file has been automatically deleted
    expect(file_exists($tempDir . '/import_' . $token . '.csv'))->toBeFalse();
});

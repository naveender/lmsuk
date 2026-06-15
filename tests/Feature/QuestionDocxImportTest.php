<?php

use App\Models\User;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Question;
use App\Services\DocxQuestionParser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

/**
 * Generate a dummy DOCX file content.
 */
function createDummyDocx($questionText = 'What is 1+1?')
{
    $tempFile = tempnam(sys_get_temp_dir(), 'docx');
    $zip = new ZipArchive();
    $zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    $documentXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
            xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
            xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">
    <w:body>
        <w:p>
            <w:r><w:t>**Q.1)** ' . htmlspecialchars($questionText) . '</w:t></w:r>
        </w:p>
        <w:p>
            <w:r><w:t>$$) Question Images</w:t></w:r>
            <w:r>
                <w:drawing>
                    <a:blip r:embed="rIdQuestionImg"/>
                </w:drawing>
            </w:r>
        </w:p>
        <w:p>
            <w:r><w:t>a) 1</w:t></w:r>
        </w:p>
        <w:p>
            <w:r><w:t>*b) 2</w:t></w:r>
        </w:p>
        <w:p>
            <w:r><w:t>c) 3</w:t></w:r>
        </w:p>
        <w:p>
            <w:r><w:t>##) Math addition rules</w:t></w:r>
        </w:p>
        <w:p>
            <w:r><w:t>#*#) Explanation Images</w:t></w:r>
            <w:r>
                <w:drawing>
                    <a:blip r:embed="rIdExplImg"/>
                </w:drawing>
            </w:r>
        </w:p>
        <w:p>
            <w:r><w:t>---</w:t></w:r>
        </w:p>
    </w:body>
</w:document>';

    $relsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rIdQuestionImg" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/image1.png"/>
    <Relationship Id="rIdExplImg" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/image2.png"/>
</Relationships>';

    $zip->addFromString('word/document.xml', $documentXml);
    $zip->addFromString('word/_rels/document.xml.rels', $relsXml);
    $zip->addFromString('word/media/image1.png', 'fake-png-data-question-image');
    $zip->addFromString('word/media/image2.png', 'fake-png-data-explanation-image');
    $zip->close();

    return $tempFile;
}

test('docx question parser parses structure and extracts images', function () {
    Storage::fake('public');

    $docxPath = createDummyDocx();
    $questions = DocxQuestionParser::parse($docxPath);

    @unlink($docxPath);

    expect($questions)->toHaveCount(1);
    $q = $questions[0];

    expect($q['title'])->toBe('What is 1+1?');
    expect($q['explanation'])->toBe('Math addition rules');
    expect($q['type'])->toBe('single_choice_radio');
    
    // Check options
    expect($q['options'])->toHaveCount(3);
    expect($q['options'][0]['option_text'])->toBe('1');
    expect($q['options'][0]['is_correct'])->toBeFalse();
    expect($q['options'][1]['option_text'])->toBe('2');
    expect($q['options'][1]['is_correct'])->toBeTrue();

    // Check images exist in public storage
    expect($q['images'])->toHaveCount(1);
    expect($q['explanation_images'])->toHaveCount(1);

    $qImg = $q['images'][0];
    $eImg = $q['explanation_images'][0];

    Storage::disk('public')->assertExists($qImg);
    Storage::disk('public')->assertExists($eImg);

    expect(Storage::disk('public')->get($qImg))->toBe('fake-png-data-question-image');
    expect(Storage::disk('public')->get($eImg))->toBe('fake-png-data-explanation-image');
});

test('admin can upload and parse docx via import-parse', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_docx_test']);
    $subject = Subject::create(['title' => 'Math', 'is_active' => true]);

    $docxPath = createDummyDocx();
    $file = new UploadedFile($docxPath, 'questions.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', null, true);

    $response = $this->actingAs($admin)
        ->postJson(route('admin.questions.import-parse'), [
            'file' => $file,
            'subject_id' => $subject->id,
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

    // Verify chunk processing works
    $token = $data['import_token'];
    $responseChunk = $this->actingAs($admin)
        ->postJson(route('admin.questions.import-process'), [
            'import_token' => $token,
            'offset' => 0,
            'limit' => 1
        ]);

    $responseChunk->assertStatus(200);
    $chunkData = $responseChunk->json();
    expect($chunkData['success'])->toBeTrue();
    expect($chunkData['results']['success_count'])->toBe(1);
    expect($chunkData['results']['completed'])->toBeTrue();

    // Verify database entries
    $dbQuestion = Question::first();
    expect($dbQuestion)->not->toBeNull();
    expect($dbQuestion->title)->toBe('What is 1+1?');
    expect($dbQuestion->subject_id)->toBe($subject->id);
    expect($dbQuestion->type)->toBe('single_choice_radio');
    expect($dbQuestion->options)->toHaveCount(3);
    expect($dbQuestion->options[1]->is_correct)->toBeTrue();

    // Verify images are stored in public storage and paths are mapped
    expect($dbQuestion->images)->toHaveCount(1);
    expect($dbQuestion->explanation_images)->toHaveCount(1);
    expect($dbQuestion->image)->toBe($dbQuestion->images[0]); // Legacy mapping
    Storage::disk('public')->assertExists($dbQuestion->image);
});

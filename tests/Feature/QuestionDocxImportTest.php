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
 * Generate a dummy DOCX file content for Template 1.
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

/**
 * Generate a dummy DOCX file content for Template 2 (Metadata).
 */
function createDummyDocxTemplate2($questionText = 'What is 2+2?')
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
            <w:r><w:t>Q.1) ' . htmlspecialchars($questionText) . '</w:t></w:r>
        </w:p>
        <w:p>
            <w:r><w:t>[1] 3</w:t></w:r>
        </w:p>
        <w:p>
            <w:r><w:t>*[2] 4</w:t></w:r>
        </w:p>
        <w:p>
            <w:r><w:t>[MARKS] 3</w:t></w:r>
        </w:p>
        <w:p>
            <w:r><w:t>[QUESTION TYPE] SC</w:t></w:r>
        </w:p>
        <w:p>
            <w:r><w:t>[NEGATIVE MARKS] 0.75</w:t></w:r>
        </w:p>
        <w:p>
            <w:r><w:t>[EXPLANATION] Basic math addition.</w:t></w:r>
        </w:p>
        <w:p>
            <w:r><w:t>[IMAGE]</w:t></w:r>
        </w:p>
        <w:p>
            <w:r>
                <w:drawing>
                    <a:blip r:embed="rIdQuestionImg"/>
                </w:drawing>
            </w:r>
        </w:p>
    </w:body>
</w:document>';

    $relsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rIdQuestionImg" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/image1.png"/>
</Relationships>';

    $zip->addFromString('word/document.xml', $documentXml);
    $zip->addFromString('word/_rels/document.xml.rels', $relsXml);
    $zip->addFromString('word/media/image1.png', 'fake-png-data-template2');
    $zip->close();

    return $tempFile;
}

/**
 * Generate a dummy DOCX file content for Template 3 (Table-based).
 */
function createDummyDocxTemplate3()
{
    $tempFile = tempnam(sys_get_temp_dir(), 'docx');
    $zip = new ZipArchive();
    $zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    $documentXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
            xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
            xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">
    <w:body>
        <w:tbl>
            <w:tr>
                <w:tc><w:p><w:r><w:t>Question</w:t></w:r></w:p></w:tc>
                <w:tc>
                    <w:p><w:r><w:t>A triangle has ##3## sides</w:t></w:r></w:p>
                    <w:p>
                        <w:r>
                            <w:drawing>
                                <a:blip r:embed="rIdQuestionImg"/>
                            </w:drawing>
                        </w:r>
                    </w:p>
                </w:tc>
            </w:tr>
            <w:tr>
                <w:tc><w:p><w:r><w:t>Explanation</w:t></w:r></w:p></w:tc>
                <w:tc><w:p><w:r><w:t>A triangle is a 3-sided polygon.</w:t></w:r></w:p></w:tc>
            </w:tr>
            <w:tr>
                <w:tc><w:p><w:r><w:t>Type</w:t></w:r></w:p></w:tc>
                <w:tc><w:p><w:r><w:t>FB</w:t></w:r></w:p></w:tc>
            </w:tr>
            <w:tr>
                <w:tc><w:p><w:r><w:t>Marks</w:t></w:r></w:p></w:tc>
                <w:tc><w:p><w:r><w:t>2</w:t></w:r></w:p></w:tc>
                <w:tc><w:p><w:r><w:t>25%</w:t></w:r></w:p></w:tc>
            </w:tr>
        </w:tbl>
    </w:body>
</w:document>';

    $relsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rIdQuestionImg" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/image1.png"/>
</Relationships>';

    $zip->addFromString('word/document.xml', $documentXml);
    $zip->addFromString('word/_rels/document.xml.rels', $relsXml);
    $zip->addFromString('word/media/image1.png', 'fake-png-data-template3');
    $zip->close();

    return $tempFile;
}

test('docx question parser parses Template 1 structure and extracts images', function () {
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

test('docx question parser parses Template 2 structure and extracts images', function () {
    Storage::fake('public');

    $docxPath = createDummyDocxTemplate2();
    $questions = DocxQuestionParser::parse($docxPath);

    @unlink($docxPath);

    expect($questions)->toHaveCount(1);
    $q = $questions[0];

    expect($q['title'])->toBe('What is 2+2?');
    expect($q['explanation'])->toBe('Basic math addition.');
    expect($q['type'])->toBe('single_choice_radio');
    expect($q['marks'])->toBe(3);
    expect($q['negative_marks'])->toBe(0.75);
    
    // Check options
    expect($q['options'])->toHaveCount(2);
    expect($q['options'][0]['option_text'])->toBe('3');
    expect($q['options'][0]['is_correct'])->toBeFalse();
    expect($q['options'][1]['option_text'])->toBe('4');
    expect($q['options'][1]['is_correct'])->toBeTrue();

    // Check images exist in public storage
    expect($q['images'])->toHaveCount(1);
    $qImg = $q['images'][0];
    Storage::disk('public')->assertExists($qImg);
    expect(Storage::disk('public')->get($qImg))->toBe('fake-png-data-template2');
});

test('docx question parser parses Template 3 structure and fill-in-the-blanks', function () {
    Storage::fake('public');

    $docxPath = createDummyDocxTemplate3();
    $questions = DocxQuestionParser::parse($docxPath);

    @unlink($docxPath);

    expect($questions)->toHaveCount(1);
    $q = $questions[0];

    expect($q['title'])->toBe('A triangle has ___ sides');
    expect($q['explanation'])->toBe('A triangle is a 3-sided polygon.');
    expect($q['type'])->toBe('fill_in_the_blanks');
    expect($q['marks'])->toBe(2);
    expect($q['negative_marks'])->toBe(0.5); // 2 * 25%
    expect($q['metadata']['blank_answers'])->toBe(['3']);

    // Check images exist in public storage
    expect($q['images'])->toHaveCount(1);
    $qImg = $q['images'][0];
    Storage::disk('public')->assertExists($qImg);
    expect(Storage::disk('public')->get($qImg))->toBe('fake-png-data-template3');
});

test('admin can upload and parse docx via import-parse for all templates', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_docx_test_multi']);
    $subject = Subject::create(['title' => 'Math', 'is_active' => true]);

    // Test Template 2
    $docxPath2 = createDummyDocxTemplate2();
    $file2 = new UploadedFile($docxPath2, 'questions_t2.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', null, true);

    $response2 = $this->actingAs($admin)
        ->postJson(route('admin.questions.import-parse'), [
            'file' => $file2,
            'subject_id' => $subject->id,
        ]);

    $response2->assertStatus(200);
    $data2 = $response2->json();
    $token2 = $data2['import_token'];

    $responseChunk2 = $this->actingAs($admin)
        ->postJson(route('admin.questions.import-process'), [
            'import_token' => $token2,
            'offset' => 0,
            'limit' => 1
        ]);

    $responseChunk2->assertStatus(200);
    
    // Verify Template 2 stored correctly with negative marks
    $dbQuestion2 = Question::where('title', 'What is 2+2?')->first();
    expect($dbQuestion2)->not->toBeNull();
    expect($dbQuestion2->marks)->toBe(3);
    expect($dbQuestion2->metadata['negative_marks'])->toBe(0.75);

    // Test Template 3
    $docxPath3 = createDummyDocxTemplate3();
    $file3 = new UploadedFile($docxPath3, 'questions_t3.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', null, true);

    $response3 = $this->actingAs($admin)
        ->postJson(route('admin.questions.import-parse'), [
            'file' => $file3,
            'subject_id' => $subject->id,
        ]);

    $response3->assertStatus(200);
    $data3 = $response3->json();
    $token3 = $data3['import_token'];

    $responseChunk3 = $this->actingAs($admin)
        ->postJson(route('admin.questions.import-process'), [
            'import_token' => $token3,
            'offset' => 0,
            'limit' => 1
        ]);

    $responseChunk3->assertStatus(200);

    // Verify Template 3 stored correctly with blanks
    $dbQuestion3 = Question::where('title', 'A triangle has ___ sides')->first();
    expect($dbQuestion3)->not->toBeNull();
    expect($dbQuestion3->type)->toBe('fill_in_the_blanks');
    expect($dbQuestion3->marks)->toBe(2);
    expect($dbQuestion3->metadata['negative_marks'])->toBe(0.5);
    expect($dbQuestion3->metadata['blank_answers'])->toBe(['3']);
});

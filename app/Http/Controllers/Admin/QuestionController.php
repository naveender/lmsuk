<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    /**
     * Display a listing of questions with filters.
     */
    public function index(Request $request)
    {
        $query = Question::with(['subject', 'topic', 'subtopic']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $questions = $query->latest()->paginate(10)->withQueryString();
        $subjects = Subject::where('is_active', true)->orderBy('title')->get();

        return view('admin.questions.index', compact('questions', 'subjects'));
    }

    /**
     * Show the form for creating a new question.
     */
    public function create()
    {
        $subjects = Subject::where('is_active', true)->orderBy('title')->get();
        return view('admin.questions.create', compact('subjects'));
    }

    /**
     * Store a newly created question.
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules($request));

        DB::beginTransaction();

        try {
            // Handle question image upload (legacy single)
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('questions', 'public');
            }

            // Handle multiple question images
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    if ($img && $img->isValid()) {
                        $imagePaths[] = $img->store('questions', 'public');
                    }
                }
            }

            // Handle multiple explanation images (separate from rich text)
            $explanationImagePaths = [];
            if ($request->hasFile('explanation_images')) {
                foreach ($request->file('explanation_images') as $img) {
                    if ($img && $img->isValid()) {
                        $explanationImagePaths[] = $img->store('questions/explanation', 'public');
                    }
                }
            }

            // Build metadata based on type
            $metadata = $this->buildMetadata($request);

            $question = Question::create([
                'title'              => $validated['title'],
                'description'        => $request->description,
                'type'               => $validated['type'],
                'subject_id'         => $request->subject_id,
                'topic_id'           => $request->topic_id,
                'subtopic_id'        => $request->subtopic_id,
                'difficulty'         => $request->difficulty,
                'marks'              => $request->marks ?? 1,
                'explanation'        => $request->explanation,
                'explanation_images' => !empty($explanationImagePaths) ? $explanationImagePaths : null,
                'metadata'           => $metadata,
                'image'              => $imagePath,
                'images'             => !empty($imagePaths) ? $imagePaths : null,
                'is_active'          => $request->has('is_active'),
            ]);

            // Save options for choice-based types
            if (in_array($validated['type'], ['single_choice_radio', 'single_choice_dropdown', 'multiple_choice', 'picture_choice'])) {
                $this->saveOptions($request, $question);
            }

            DB::commit();

            if ($request->input('save_and_add_another') == '1') {
                return redirect()->route('admin.questions.create')->with('success', 'Question created successfully. Add another one!');
            }

            return redirect()->route('admin.questions.index')->with('success', 'Question created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create question: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified question.
     */
    public function edit(Question $question)
    {
        $question->load(['options', 'subject', 'topic', 'subtopic']);
        $subjects = Subject::where('is_active', true)->orderBy('title')->get();

        // Load topics for the selected subject
        $topics = collect();
        if ($question->subject_id) {
            $topics = Topic::where('subject_id', $question->subject_id)
                ->where(function ($q) {
                    $q->whereNull('parent')->orWhere('parent', 0);
                })
                ->orderBy('name')
                ->get();
        }

        // Load subtopics for the selected topic
        $subtopics = collect();
        if ($question->topic_id) {
            $subtopics = Topic::where('parent', $question->topic_id)->orderBy('name')->get();
        }

        return view('admin.questions.edit', compact('question', 'subjects', 'topics', 'subtopics'));
    }

    /**
     * Update the specified question.
     */
    public function update(Request $request, Question $question)
    {
        $validated = $request->validate($this->validationRules($request));

        DB::beginTransaction();

        try {
            // Handle multiple question images
            $imagePaths = $question->images ?? [];
            if ($request->hasFile('images')) {
                $removedImages = $request->input('removed_images', []);
                foreach ($removedImages as $removedImg) {
                    if (Storage::disk('public')->exists($removedImg)) {
                        Storage::disk('public')->delete($removedImg);
                    }
                    $imagePaths = array_values(array_filter($imagePaths, fn($p) => $p !== $removedImg));
                }
                foreach ($request->file('images') as $img) {
                    if ($img && $img->isValid()) {
                        $imagePaths[] = $img->store('questions', 'public');
                    }
                }
            } else {
                $removedImages = $request->input('removed_images', []);
                foreach ($removedImages as $removedImg) {
                    if (Storage::disk('public')->exists($removedImg)) {
                        Storage::disk('public')->delete($removedImg);
                    }
                    $imagePaths = array_values(array_filter($imagePaths, fn($p) => $p !== $removedImg));
                }
            }

            // Handle multiple explanation images (separate from rich text)
            $explanationImagePaths = $question->explanation_images ?? [];
            if ($request->hasFile('explanation_images')) {
                $removedExpImgs = $request->input('removed_explanation_images', []);
                foreach ($removedExpImgs as $removedImg) {
                    if (Storage::disk('public')->exists($removedImg)) {
                        Storage::disk('public')->delete($removedImg);
                    }
                    $explanationImagePaths = array_values(array_filter($explanationImagePaths, fn($p) => $p !== $removedImg));
                }
                foreach ($request->file('explanation_images') as $img) {
                    if ($img && $img->isValid()) {
                        $explanationImagePaths[] = $img->store('questions/explanation', 'public');
                    }
                }
            } else {
                $removedExpImgs = $request->input('removed_explanation_images', []);
                foreach ($removedExpImgs as $removedImg) {
                    if (Storage::disk('public')->exists($removedImg)) {
                        Storage::disk('public')->delete($removedImg);
                    }
                    $explanationImagePaths = array_values(array_filter($explanationImagePaths, fn($p) => $p !== $removedImg));
                }
            }

            // Build metadata
            $metadata = $this->buildMetadata($request);

            // Handle single legacy image
            $imagePath = $question->image;
            if ($request->hasFile('image')) {
                if ($question->image && Storage::disk('public')->exists($question->image)) {
                    Storage::disk('public')->delete($question->image);
                }
                $imagePath = $request->file('image')->store('questions', 'public');
            }
            if ($request->has('remove_image') && $question->image) {
                if (Storage::disk('public')->exists($question->image)) {
                    Storage::disk('public')->delete($question->image);
                }
                $imagePath = null;
            }

            $question->update([
                'title'              => $validated['title'],
                'description'        => $request->description,
                'type'               => $validated['type'],
                'subject_id'         => $request->subject_id,
                'topic_id'           => $request->topic_id,
                'subtopic_id'        => $request->subtopic_id,
                'difficulty'         => $request->difficulty,
                'marks'              => $request->marks ?? 1,
                'explanation'        => $request->explanation,
                'explanation_images' => !empty($explanationImagePaths) ? array_values($explanationImagePaths) : null,
                'metadata'           => $metadata,
                'image'              => $imagePath,
                'images'             => !empty($imagePaths) ? array_values($imagePaths) : null,
                'is_active'          => $request->has('is_active'),
            ]);

            // Update options for choice-based types
            if (in_array($validated['type'], ['single_choice_radio', 'single_choice_dropdown', 'multiple_choice', 'picture_choice'])) {
                // Delete only old option images that are not being kept
                $keptImages = $request->input('existing_option_images', []);
                foreach ($question->options as $opt) {
                    if ($opt->option_image && !in_array($opt->option_image, $keptImages)) {
                        if (Storage::disk('public')->exists($opt->option_image)) {
                            Storage::disk('public')->delete($opt->option_image);
                        }
                    }
                }
                $question->options()->delete();
                $this->saveOptions($request, $question);
            } else {
                // Clean up options if type changed away from choice-based
                foreach ($question->options as $opt) {
                    if ($opt->option_image && Storage::disk('public')->exists($opt->option_image)) {
                        Storage::disk('public')->delete($opt->option_image);
                    }
                }
                $question->options()->delete();
            }

            DB::commit();
            return redirect()->route('admin.questions.index')->with('success', 'Question updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update question: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified question.
     */
    public function destroy(Question $question)
    {
        // Clean up single image
        if ($question->image && Storage::disk('public')->exists($question->image)) {
            Storage::disk('public')->delete($question->image);
        }

        // Clean up multiple question images
        foreach ($question->images ?? [] as $img) {
            if (Storage::disk('public')->exists($img)) {
                Storage::disk('public')->delete($img);
            }
        }

        // Clean up explanation images
        foreach ($question->explanation_images ?? [] as $img) {
            if (Storage::disk('public')->exists($img)) {
                Storage::disk('public')->delete($img);
            }
        }

        foreach ($question->options as $opt) {
            if ($opt->option_image && Storage::disk('public')->exists($opt->option_image)) {
                Storage::disk('public')->delete($opt->option_image);
            }
        }

        $question->delete(); // cascade deletes options

        return redirect()->route('admin.questions.index')->with('success', 'Question deleted successfully.');
    }

    /**
     * AJAX: Upload an image for the Quill rich text editor.
     */
    public function uploadImage(Request $request)
    {
        $request->validate(['image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096']);

        $path = $request->file('image')->store('questions/rich', 'public');

        return response()->json([
            'url' => asset('storage/' . $path),
        ]);
    }

    /**
     * AJAX: Get topics for a subject.
     */
    public function getTopics($subjectId)
    {
        $topics = Topic::where('subject_id', $subjectId)
            ->where(function ($q) {
                $q->whereNull('parent')->orWhere('parent', 0);
            })
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($topics);
    }

    /**
     * AJAX: Get subtopics for a topic.
     */
    public function getSubtopics($topicId)
    {
        $subtopics = Topic::where('parent', $topicId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($subtopics);
    }

    /**
     * Build validation rules based on question type.
     */
    private function validationRules(Request $request): array
    {
        $rules = [
            'title'                 => 'required|string|max:1000',
            'type'                  => 'required|in:' . implode(',', array_keys(Question::TYPES)),
            'marks'                 => 'nullable|integer|min:1',
            'image'                 => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images'                => 'nullable|array',
            'images.*'              => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'explanation_images'    => 'nullable|array',
            'explanation_images.*'  => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];

        $type = $request->input('type');

        if (in_array($type, ['single_choice_radio', 'single_choice_dropdown', 'multiple_choice'])) {
            $rules['options']   = 'required|array|min:2';
            $rules['options.*'] = 'required|string|max:1000';
            $rules['correct']   = 'required';
        }

        if ($type === 'picture_choice') {
            $isUpdate = $request->isMethod('put') || $request->isMethod('patch');
            $rules['option_images']   = $isUpdate ? 'nullable|array' : 'required|array|min:2';
            $rules['option_images.*'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            $rules['correct']         = 'required';
        }

        if ($type === 'fill_in_the_blanks') {
            $rules['blank_answers']   = 'required|array|min:1';
            $rules['blank_answers.*'] = 'required|string|max:500';
        }

        if (in_array($type, ['matching_drag_drop', 'matching_text'])) {
            $rules['match_left']    = 'required|array|min:2';
            $rules['match_left.*']  = 'required|string|max:500';
            $rules['match_right']   = 'required|array|min:2';
            $rules['match_right.*'] = 'required|string|max:500';
        }

        return $rules;
    }

    /**
     * Build the metadata JSON based on question type.
     */
    private function buildMetadata(Request $request): ?array
    {
        $type = $request->input('type');
        $metadata = [];

        switch ($type) {
            case 'fill_in_the_blanks':
                $metadata['blank_answers'] = array_values(array_filter($request->input('blank_answers', [])));
                break;

            case 'matching_drag_drop':
            case 'matching_text':
                $left  = $request->input('match_left', []);
                $right = $request->input('match_right', []);
                $pairs = [];
                foreach ($left as $i => $l) {
                    if (!empty($l) && !empty($right[$i])) {
                        $pairs[] = ['left' => $l, 'right' => $right[$i]];
                    }
                }
                $metadata['matching_pairs'] = $pairs;
                break;

            case 'free_text':
                $metadata['word_limit'] = $request->input('word_limit');
                break;

            case 'file_upload':
                $metadata['allowed_file_types'] = $request->input('allowed_file_types', []);
                $metadata['max_file_size']      = $request->input('max_file_size', 5);
                break;
        }

        return !empty($metadata) ? $metadata : null;
    }

    /**
     * Save options for choice-based question types.
     */
    private function saveOptions(Request $request, Question $question): void
    {
        $type    = $request->input('type');
        $correct = $request->input('correct', []);

        if ($type === 'picture_choice') {
            $optionImages = $request->file('option_images', []);
            $optionTexts  = $request->input('option_texts', []);

            foreach ($optionImages as $index => $imgFile) {
                $imgPath = null;
                if ($imgFile) {
                    $imgPath = $imgFile->store('question_options', 'public');
                }

                // For picture choice, check if existing images are being kept
                $existingImage = $request->input("existing_option_images.{$index}");

                QuestionOption::create([
                    'question_id'  => $question->id,
                    'option_text'  => $optionTexts[$index] ?? null,
                    'option_image' => $imgPath ?? $existingImage,
                    'is_correct'   => is_array($correct) ? in_array((string)$index, $correct) : (string)$correct === (string)$index,
                    'sort_order'   => $index,
                ]);
            }

            // Handle options with existing images but no new upload
            $existingImages = $request->input('existing_option_images', []);
            foreach ($existingImages as $index => $existingImg) {
                if (!isset($optionImages[$index]) && $existingImg) {
                    QuestionOption::create([
                        'question_id'  => $question->id,
                        'option_text'  => $optionTexts[$index] ?? null,
                        'option_image' => $existingImg,
                        'is_correct'   => is_array($correct) ? in_array((string)$index, $correct) : (string)$correct === (string)$index,
                        'sort_order'   => $index,
                    ]);
                }
            }
        } else {
            $options = $request->input('options', []);

            foreach ($options as $index => $optionText) {
                if (empty($optionText)) continue;

                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $optionText,
                    'is_correct'  => is_array($correct) ? in_array((string)$index, $correct) : (string)$correct === (string)$index,
                    'sort_order'  => $index,
                ]);
            }
        }
    }

    /**
     * Show the question import form.
     */
    public function showImportForm()
    {
        $subjects = Subject::where('is_active', true)->orderBy('title')->get();
        return view('admin.questions.import', compact('subjects'));
    }

    /**
     * Download a sample CSV import file.
     */
    public function downloadSample()
    {
        $headers = [
            'title', 'description', 'type', 'subject', 'topic', 'subtopic', 'difficulty', 'marks', 'explanation',
            'option_1', 'option_2', 'option_3', 'option_4', 'option_5', 'correct_options',
            'blank_answers', 'match_left_1', 'match_right_1', 'match_left_2', 'match_right_2',
            'match_left_3', 'match_right_3', 'match_left_4', 'match_right_4',
            'word_limit', 'allowed_file_types', 'max_file_size'
        ];

        $samples = [
            [
                'Identify the synonym of the word \'diligent\'.',
                'Choose the most appropriate synonym from the options below.',
                'single_choice_radio',
                'English',
                'Vocabulary & Synonyms',
                'Synonyms & Antonyms',
                'easy',
                '1',
                '\'Diligent\' means showing care and conscientiousness in one\'s work or duties. \'Hardworking\' is its closest synonym.',
                'Hardworking', 'Lazy', 'Careless', 'Sleepy', '',
                '1', // Option 1 (Hardworking) is correct
                '', // blank_answers
                '', '', '', '', '', '', '', '', // match_left/right 1-4
                '', '', '' // word_limit, allowed_file_types, max_file_size
            ],
            [
                'Select all equivalent values of 1/2.',
                'Select all options that are equal to 0.5.',
                'multiple_choice',  
                'Maths & Number Reasoning',
                'Numbers & Place Value',
                'Decimals & Fractions',
                'medium',
                '2',
                '0.5, 2/4, and 50% are all equivalent to 1/2.',
                '0.5', '2/4', '0.05', '0.25', '',
                '1,2', // Options 1 and 2 are correct
                '', // blank_answers
                '', '', '', '', '', '', '', '', // match_left/right 1-4
                '', '', '' // word_limit, allowed_file_types, max_file_size
            ],
            [
                'Fill in the blank with correct word.',
                'The past tense of the word \'go\' is ___ and the past participle is ___.',
                'fill_in_the_blanks',
                'English',
                'Vocabulary & Synonyms',
                'Synonyms & Antonyms',
                'medium',
                '2',
                'The past tense of go is went, and the past participle is gone.',
                '', '', '', '', '', // options 1-5
                '', // correct_options
                'went|gone', // blank_answers
                '', '', '', '', '', '', '', '', // match_left/right 1-4
                '', '', '' // word_limit, allowed_file_types, max_file_size
            ],
            [
                'Match the words with their antonyms.',
                'Match the items on the left with their correct opposite words on the right.',
                'matching_text',
                'Verbal Reasoning',
                'Analogies',
                'Word Relationships',
                'easy',
                '2',
                'The antonym of Hot is Cold, High is Low, and Fast is Slow.',
                '', '', '', '', '', // options 1-5
                '', // correct_options
                '', // blank_answers
                'Hot', 'Cold', 'High', 'Low', 'Fast', 'Slow', '', '', // match_left/right 1-4
                '', '', '' // word_limit, allowed_file_types, max_file_size
            ],
            [
                'Write an essay about your favorite book.',
                'Explain the plot, main characters, and why you would recommend it to others.',
                'free_text',
                'English',
                'Reading Comprehension',
                'Inference & Deduction',
                'hard',
                '5',
                'Grading should consider vocabulary usage, sentence structure, and coherence of arguments.',
                '', '', '', '', '', // options 1-5
                '', // correct_options
                '', // blank_answers
                '', '', '', '', '', '', '', '', // match_left/right 1-4
                '500', '', '' // word_limit, allowed_file_types, max_file_size
            ]
        ];

        $callback = function() use ($headers, $samples) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            foreach ($samples as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->streamDownload($callback, 'questions_import_sample.csv', [
            'Content-Type' => 'text/csv',
            'Cache-Control' => 'no-cache, must-revalidate',
            'Expires' => '0',
        ]);
    }

    /**
     * Download sample DOCX Template 1.
     */
    public function downloadSampleDocxTemplate1()
    {
        $path = public_path('importing_templates/template1.docx');
        if (!file_exists($path)) {
            abort(404, 'Template 1 file not found.');
        }
        return response()->download($path, 'template1.docx');
    }

    /**
     * Download sample DOCX Template 2.
     */
    public function downloadSampleDocxTemplate2()
    {
        $path = public_path('importing_templates/template2.docx');
        if (!file_exists($path)) {
            abort(404, 'Template 2 file not found.');
        }
        return response()->download($path, 'template2.docx');
    }

    /**
     * Download sample DOCX Template 3.
     */
    public function downloadSampleDocxTemplate3()
    {
        $path = public_path('importing_templates/template3.docx');
        if (!file_exists($path)) {
            abort(404, 'Template 3 file not found.');
        }
        return response()->download($path, 'template3.docx');
    }

    /**
     * Upload and parse the import file to count rows.
     */
    public function parseImportFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,docx|max:10240', // max 10MB
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        
        // Generate unique token
        $token = \Illuminate\Support\Str::random(32);
        $tempDir = storage_path('app/temp_imports');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Handle DOCX parser
        if ($extension === 'docx') {
            try {
                $tempDocxPath = $tempDir . '/import_' . $token . '.docx';
                $file->move($tempDir, 'import_' . $token . '.docx');

                $questions = \App\Services\DocxQuestionParser::parse($tempDocxPath);

                if (file_exists($tempDocxPath)) {
                    unlink($tempDocxPath);
                }

                $tempJsonPath = $tempDir . '/import_' . $token . '.json';
                $jsonData = [
                    'subject_id'  => $request->input('subject_id'),
                    'topic_id'    => $request->input('topic_id'),
                    'subtopic_id' => $request->input('subtopic_id'),
                    'questions'   => $questions
                ];
                file_put_contents($tempJsonPath, json_encode($jsonData));

                return response()->json([
                    'success' => true,
                    'import_token' => $token,
                    'total_rows' => count($questions),
                    'headers' => ['docx'],
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to parse the DOCX file: ' . $e->getMessage(),
                ], 422);
            }
        }
        
        $tempPath = $tempDir . '/import_' . $token . '.csv';
        $file->move($tempDir, 'import_' . $token . '.csv');

        // Save CSV metadata (selected category fallback)
        $metaPath = $tempDir . '/import_' . $token . '_meta.json';
        $metaData = [
            'subject_id'  => $request->input('subject_id'),
            'topic_id'    => $request->input('topic_id'),
            'subtopic_id' => $request->input('subtopic_id'),
        ];
        file_put_contents($metaPath, json_encode($metaData));

        // Parse file to validate and count rows
        $rowCount = 0;
        $headers = [];
        if (($handle = fopen($tempPath, 'r')) !== false) {
            // Read headers
            if (($data = fgetcsv($handle)) !== false) {
                $headers = array_map('trim', $data);
            }
            // Count rows
            while (fgetcsv($handle) !== false) {
                $rowCount++;
            }
            fclose($handle);
        }

        // Validate required headers
        $required = ['title', 'type'];
        $missing = [];
        $lowerHeaders = array_map('strtolower', $headers);
        foreach ($required as $req) {
            if (!in_array($req, $lowerHeaders)) {
                $missing[] = $req;
            }
        }

        if (!empty($missing)) {
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
            if (file_exists($metaPath)) {
                unlink($metaPath);
            }
            return response()->json([
                'success' => false,
                'message' => 'Missing required CSV columns: ' . implode(', ', $missing),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'import_token' => $token,
            'total_rows' => $rowCount,
            'headers' => $headers,
        ]);
    }

    /**
     * Process a single chunk of rows during question import.
     */
    public function processImportChunk(Request $request)
    {
        $request->validate([
            'import_token' => 'required|string',
            'offset' => 'required|integer|min:0',
            'limit' => 'required|integer|min:1',
        ]);

        $token = $request->input('import_token');
        $offset = intval($request->input('offset'));
        $limit = intval($request->input('limit'));

        $tempJsonPath = storage_path('app/temp_imports/import_' . $token . '.json');
        $tempCsvPath = storage_path('app/temp_imports/import_' . $token . '.csv');

        if (!file_exists($tempJsonPath) && !file_exists($tempCsvPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Import session expired or file not found.',
            ], 400);
        }

        // Process DOCX / JSON questions
        if (file_exists($tempJsonPath)) {
            $jsonData = json_decode(file_get_contents($tempJsonPath), true);
            $questions = $jsonData['questions'] ?? [];

            $globalSubjectId = $jsonData['subject_id'] ?? null;
            $globalTopicId = $jsonData['topic_id'] ?? null;
            $globalSubtopicId = $jsonData['subtopic_id'] ?? null;

            $results = [
                'processed' => 0,
                'success_count' => 0,
                'failed_count' => 0,
                'errors' => [],
                'warnings' => [],
            ];

            $chunk = array_slice($questions, $offset, $limit);
            $processedInChunk = 0;

            foreach ($chunk as $index => $q) {
                $processedInChunk++;
                $rowNumber = $offset + $processedInChunk;

                $title = $q['title'] ?? '';
                $type = $q['type'] ?? 'single_choice_radio';

                if (empty($title)) {
                    $results['errors'][] = [
                        'row' => $rowNumber,
                        'message' => 'Question title is missing.',
                    ];
                    $results['failed_count']++;
                    continue;
                }

                try {
                    DB::beginTransaction();

                    $metadata = !empty($q['metadata']) ? $q['metadata'] : [];
                    if (isset($q['negative_marks'])) {
                        $metadata['negative_marks'] = $q['negative_marks'];
                    }

                    $qImages = !empty($q['images']) ? $q['images'] : [];
                    $singleImage = null;
                    $multipleImages = null;
                    if (count($qImages) > 0) {
                        $singleImage = $qImages[0];
                        if (count($qImages) > 1) {
                            $multipleImages = array_slice($qImages, 1);
                        }
                    }

                    $question = Question::create([
                        'title'              => $title,
                        'description'        => $q['description'] ?? $title,
                        'type'               => $type,
                        'subject_id'         => $globalSubjectId,
                        'topic_id'           => $globalTopicId,
                        'subtopic_id'        => $globalSubtopicId,
                        'difficulty'         => $q['difficulty'] ?? 'easy',
                        'marks'              => $q['marks'] ?? 1,
                        'explanation'        => $q['explanation'] ?? null,
                        'explanation_images' => !empty($q['explanation_images']) ? $q['explanation_images'] : null,
                        'images'             => $multipleImages,
                        'image'              => $singleImage,
                        'is_active'          => true,
                        'metadata'           => !empty($metadata) ? $metadata : null,
                    ]);

                    if ($question->usesOptions()) {
                        foreach ($q['options'] as $optIdx => $opt) {
                            QuestionOption::create([
                                'question_id'  => $question->id,
                                'option_text'  => $opt['option_text'],
                                'is_correct'   => $opt['is_correct'],
                                'option_image' => $opt['option_image'] ?? null,
                                'sort_order'   => $optIdx,
                            ]);
                        }
                    }

                    DB::commit();
                    $results['success_count']++;
                } catch (\Exception $e) {
                    DB::rollBack();
                    $results['errors'][] = [
                        'row' => $rowNumber,
                        'message' => $e->getMessage(),
                    ];
                    $results['failed_count']++;
                }
            }

            $results['processed'] = $processedInChunk;

            if ($offset + $limit >= count($questions)) {
                if (file_exists($tempJsonPath)) {
                    unlink($tempJsonPath);
                }
                $results['completed'] = true;
            } else {
                $results['completed'] = false;
            }

            return response()->json([
                'success' => true,
                'results' => $results,
            ]);
        }

        // Process CSV questions
        $results = [
            'processed' => 0,
            'success_count' => 0,
            'failed_count' => 0,
            'errors' => [],
            'warnings' => [],
        ];

        $metaPath = storage_path('app/temp_imports/import_' . $token . '_meta.json');
        $metaData = [];
        if (file_exists($metaPath)) {
            $metaData = json_decode(file_get_contents($metaPath), true) ?: [];
        }

        if (($handle = fopen($tempCsvPath, 'r')) !== false) {
            // Read headers
            $headers = fgetcsv($handle);
            $headerMap = [];
            foreach ($headers as $index => $header) {
                $normalized = strtolower(trim($header));
                $normalized = str_replace([' ', '-'], '_', $normalized);
                $headerMap[$normalized] = $index;
            }

            // Skip to offset
            $currentRow = 0;
            while ($currentRow < $offset && fgetcsv($handle) !== false) {
                $currentRow++;
            }

            // Process chunk
            $processedInChunk = 0;
            while ($processedInChunk < $limit && ($row = fgetcsv($handle)) !== false) {
                // Skip completely empty lines
                if (empty(array_filter($row))) {
                    continue;
                }
                
                $processedInChunk++;
                $rowNumber = $offset + $processedInChunk + 1; // +1 for 1-based, +1 for header

                $getValue = function($key, $default = null) use ($row, $headerMap) {
                    return isset($headerMap[$key]) && isset($row[$headerMap[$key]]) ? trim($row[$headerMap[$key]]) : $default;
                };

                $title = $getValue('title');
                $type = $getValue('type');

                if (empty($title)) {
                    $results['errors'][] = [
                        'row' => $rowNumber,
                        'message' => 'Question title is missing.',
                    ];
                    $results['failed_count']++;
                    continue;
                }

                if (empty($type)) {
                    $results['errors'][] = [
                        'row' => $rowNumber,
                        'message' => 'Question type is missing.',
                    ];
                    $results['failed_count']++;
                    continue;
                }

                // Normalize type if they wrote friendly text
                $typeMapping = [
                    'single choice radio' => 'single_choice_radio',
                    'single choice dropdown' => 'single_choice_dropdown',
                    'multiple choice' => 'multiple_choice',
                    'picture choice' => 'picture_choice',
                    'fill in the blanks' => 'fill_in_the_blanks',
                    'matching drag drop' => 'matching_drag_drop',
                    'matching text' => 'matching_text',
                    'free text' => 'free_text',
                    'file upload' => 'file_upload',
                    'single choice' => 'single_choice_radio',
                    'mcq' => 'multiple_choice',
                    'essay' => 'free_text',
                ];

                $normalizedType = strtolower(str_replace(['_', '-'], ' ', $type));
                if (isset($typeMapping[$normalizedType])) {
                    $type = $typeMapping[$normalizedType];
                }

                if (!array_key_exists($type, Question::TYPES)) {
                    $results['errors'][] = [
                        'row' => $rowNumber,
                        'message' => "Invalid question type: '{$type}'. Allowed types are: " . implode(', ', array_keys(Question::TYPES)),
                    ];
                    $results['failed_count']++;
                    continue;
                }

                // Handle fill_in_the_blanks normalization and copy question to description
                $description = $getValue('description');
                if ($type === 'fill_in_the_blanks') {
                    // Replace [blank] with ___ (three underscores) in title
                    $title = str_replace('[blank]', '___', $title);
                    
                    if (!empty($description)) {
                        $description = str_replace('[blank]', '___', $description);
                        // If description doesn't have blanks but title does, copy title to description
                        if (strpos($description, '___') === false && strpos($title, '___') !== false) {
                            $description = $title;
                        }
                    } else {
                        // If description is empty, set description to the question text (with blanks)
                        $description = $title;
                    }
                    
                    // Truncate title if it exceeds 255 chars
                    if (strlen($title) > 255) {
                        $title = mb_substr($title, 0, 250) . '...';
                    }
                } else {
                    // For other types, copy long titles to description if description is empty
                    if (empty($description) && strlen($title) > 255) {
                        $description = $title;
                    }
                    if (strlen($title) > 255) {
                        $title = mb_substr($title, 0, 250) . '...';
                    }
                }

                // Process subject, topic, subtopic with fallback
                $subjectName = $getValue('subject');
                $topicName = $getValue('topic');
                $subtopicName = $getValue('subtopic');

                $subjectId = null;
                $topicId = null;
                $subtopicId = null;

                try {
                    DB::beginTransaction();

                    if (!empty($subjectName)) {
                        $subject = Subject::whereRaw('LOWER(title) = ?', [strtolower($subjectName)])->first();
                        if (!$subject) {
                            $subject = Subject::create([
                                'title' => $subjectName,
                                'is_active' => true,
                            ]);
                            $results['warnings'][] = [
                                'row' => $rowNumber,
                                'message' => "Subject '{$subjectName}' was not found and has been created dynamically.",
                            ];
                        }
                        $subjectId = $subject->id;
                    } elseif (!empty($metaData['subject_id'])) {
                        $subjectId = intval($metaData['subject_id']);
                    }

                    if ($subjectId) {
                        if (!empty($topicName)) {
                            $topic = Topic::where('subject_id', $subjectId)
                                ->where(function($q) {
                                    $q->whereNull('parent')->orWhere('parent', 0);
                                })
                                ->whereRaw('LOWER(name) = ?', [strtolower($topicName)])
                                ->first();

                            if (!$topic) {
                                $slug = \Illuminate\Support\Str::slug($topicName);
                                $originalSlug = $slug;
                                $count = 1;
                                while (Topic::where('slug', $slug)->exists()) {
                                    $slug = $originalSlug . '-' . $count;
                                    $count++;
                                }
                                $topic = Topic::create([
                                    'name' => $topicName,
                                    'subject_id' => $subjectId,
                                    'parent' => null,
                                    'slug' => $slug,
                                ]);
                                $results['warnings'][] = [
                                    'row' => $rowNumber,
                                    'message' => "Topic '{$topicName}' was not found under '{$subjectName}' and has been created dynamically.",
                                ];
                            }
                            $topicId = $topic->id;
                        } elseif (!empty($metaData['topic_id'])) {
                            $topic = Topic::where('id', $metaData['topic_id'])->where('subject_id', $subjectId)->first();
                            if ($topic) {
                                $topicId = $topic->id;
                            }
                        }

                        if ($topicId) {
                            if (!empty($subtopicName)) {
                                $subtopic = Topic::where('subject_id', $subjectId)
                                    ->where('parent', $topicId)
                                    ->whereRaw('LOWER(name) = ?', [strtolower($subtopicName)])
                                    ->first();

                                if (!$subtopic) {
                                    $slug = \Illuminate\Support\Str::slug($subtopicName);
                                    $originalSlug = $slug;
                                    $count = 1;
                                    while (Topic::where('slug', $slug)->exists()) {
                                        $slug = $originalSlug . '-' . $count;
                                        $count++;
                                    }
                                    $subtopic = Topic::create([
                                        'name' => $subtopicName,
                                        'subject_id' => $subjectId,
                                        'parent' => $topicId,
                                        'slug' => $slug,
                                    ]);
                                    $results['warnings'][] = [
                                        'row' => $rowNumber,
                                        'message' => "Subtopic '{$subtopicName}' was not found under '{$topicName}' and has been created dynamically.",
                                    ];
                                }
                                $subtopicId = $subtopic->id;
                            } elseif (!empty($metaData['subtopic_id'])) {
                                $subtopic = Topic::where('id', $metaData['subtopic_id'])->where('parent', $topicId)->first();
                                if ($subtopic) {
                                    $subtopicId = $subtopic->id;
                                }
                            }
                        }
                    }

                    // Build metadata
                    $metadata = [];
                    if ($type === 'fill_in_the_blanks') {
                        $blankAnswersStr = $getValue('blank_answers', '');
                        if (empty($blankAnswersStr)) {
                            throw new \Exception("Fill in the blanks question requires 'blank_answers' column.");
                        }
                        $metadata['blank_answers'] = array_values(array_filter(array_map('trim', explode('|', $blankAnswersStr))));
                        if (empty($metadata['blank_answers'])) {
                            $metadata['blank_answers'] = array_values(array_filter(array_map('trim', explode(',', $blankAnswersStr))));
                        }
                    } elseif ($type === 'matching_drag_drop' || $type === 'matching_text') {
                        $pairs = [];
                        for ($i = 1; $i <= 4; $i++) {
                            $left = $getValue("match_left_{$i}");
                            $right = $getValue("match_right_{$i}");
                            if (!empty($left) && !empty($right)) {
                                $pairs[] = ['left' => $left, 'right' => $right];
                            }
                        }
                        if (empty($pairs)) {
                            throw new \Exception("Matching question requires at least one left-right match pair.");
                        }
                        $metadata['matching_pairs'] = $pairs;
                    } elseif ($type === 'free_text') {
                        $wordLimitVal = $getValue('word_limit');
                        $metadata['word_limit'] = !empty($wordLimitVal) ? intval($wordLimitVal) : null;
                    } elseif ($type === 'file_upload') {
                        $types = $getValue('allowed_file_types', 'pdf,docx,doc,jpg,png');
                        $size = $getValue('max_file_size', '5');
                        $metadata['allowed_file_types'] = array_map('trim', explode(',', $types));
                        $metadata['max_file_size'] = intval($size);
                    }

                    // Difficulty
                    $difficulty = strtolower($getValue('difficulty', 'easy'));
                    if (!in_array($difficulty, ['easy', 'medium', 'hard'])) {
                        $difficulty = 'easy';
                    }

                    // Marks
                    $marks = intval($getValue('marks', 1));
                    if ($marks <= 0) {
                        $marks = 1;
                    }

                    // Create Question
                    $question = Question::create([
                        'title' => $title,
                        'description' => $description,
                        'type' => $type,
                        'subject_id' => $subjectId,
                        'topic_id' => $topicId,
                        'subtopic_id' => $subtopicId,
                        'difficulty' => $difficulty,
                        'marks' => $marks,
                        'explanation' => $getValue('explanation'),
                        'metadata' => !empty($metadata) ? $metadata : null,
                        'is_active' => true,
                    ]);

                    // Create Options for choice-based questions
                    if (in_array($type, ['single_choice_radio', 'single_choice_dropdown', 'multiple_choice'])) {
                        $optionsList = [];
                        for ($i = 1; $i <= 5; $i++) {
                            $optText = $getValue("option_{$i}");
                            if (!empty($optText)) {
                                $optionsList[] = $optText;
                            }
                        }

                        if (count($optionsList) < 2) {
                            throw new \Exception("Choice-based question requires at least 2 options.");
                        }

                        $correctOptionsStr = $getValue('correct_options', '');
                        if (empty($correctOptionsStr)) {
                            throw new \Exception("Choice-based question requires at least 1 correct option index (e.g. 1 or A).");
                        }

                        // Parse correct options indices
                        $correctIndices = [];
                        $correctParts = explode(',', $correctOptionsStr);
                        foreach ($correctParts as $part) {
                            $part = strtoupper(trim($part));
                            if (is_numeric($part)) {
                                $correctIndices[] = intval($part) - 1; // 0-based
                            } else {
                                $code = ord($part) - ord('A'); // A=0, B=1...
                                if ($code >= 0 && $code < 26) {
                                    $correctIndices[] = $code;
                                }
                            }
                        }

                        foreach ($optionsList as $index => $optText) {
                            QuestionOption::create([
                                'question_id' => $question->id,
                                'option_text' => $optText,
                                'is_correct' => in_array($index, $correctIndices),
                                'sort_order' => $index,
                            ]);
                        }
                    }

                    DB::commit();
                    $results['success_count']++;

                } catch (\Exception $e) {
                    DB::rollBack();
                    $results['errors'][] = [
                        'row' => $rowNumber,
                        'message' => $e->getMessage(),
                    ];
                    $results['failed_count']++;
                }
            }

            $results['processed'] = $processedInChunk;
            fclose($handle);
        }

        // Clean up file if we reached the end
        $totalLines = 0;
        if (($handle = fopen($tempCsvPath, 'r')) !== false) {
            fgetcsv($handle); // skip header
            while (($row = fgetcsv($handle)) !== false) {
                if (!empty(array_filter($row))) {
                    $totalLines++;
                }
            }
            fclose($handle);
        }

        if ($offset + $limit >= $totalLines) {
            if (file_exists($tempCsvPath)) {
                unlink($tempCsvPath);
            }
            if (file_exists($metaPath)) {
                unlink($metaPath);
            }
            $results['completed'] = true;
        } else {
            $results['completed'] = false;
        }

        return response()->json([
            'success' => true,
            'results' => $results,
        ]);
    }
}


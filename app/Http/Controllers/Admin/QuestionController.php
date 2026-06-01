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
            // Handle question image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('questions', 'public');
            }

            // Build metadata based on type
            $metadata = $this->buildMetadata($request);

            $question = Question::create([
                'title'       => $validated['title'],
                'description' => $request->description,
                'type'        => $validated['type'],
                'subject_id'  => $request->subject_id,
                'topic_id'    => $request->topic_id,
                'subtopic_id' => $request->subtopic_id,
                'difficulty'  => $request->difficulty,
                'marks'       => $request->marks ?? 1,
                'explanation' => $request->explanation,
                'metadata'    => $metadata,
                'image'       => $imagePath,
                'is_active'   => $request->has('is_active'),
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
            // Handle question image
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

            // Build metadata
            $metadata = $this->buildMetadata($request);

            $question->update([
                'title'       => $validated['title'],
                'description' => $request->description,
                'type'        => $validated['type'],
                'subject_id'  => $request->subject_id,
                'topic_id'    => $request->topic_id,
                'subtopic_id' => $request->subtopic_id,
                'difficulty'  => $request->difficulty,
                'marks'       => $request->marks ?? 1,
                'explanation' => $request->explanation,
                'metadata'    => $metadata,
                'image'       => $imagePath,
                'is_active'   => $request->has('is_active'),
            ]);

            // Update options for choice-based types
            if (in_array($validated['type'], ['single_choice_radio', 'single_choice_dropdown', 'multiple_choice', 'picture_choice'])) {
                // Delete old option images
                foreach ($question->options as $opt) {
                    if ($opt->option_image && Storage::disk('public')->exists($opt->option_image)) {
                        Storage::disk('public')->delete($opt->option_image);
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
        // Clean up images
        if ($question->image && Storage::disk('public')->exists($question->image)) {
            Storage::disk('public')->delete($question->image);
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
            'title' => 'required|string|max:1000',
            'type'  => 'required|in:' . implode(',', array_keys(Question::TYPES)),
            'marks' => 'nullable|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $type = $request->input('type');

        if (in_array($type, ['single_choice_radio', 'single_choice_dropdown', 'multiple_choice'])) {
            $rules['options']   = 'required|array|min:2';
            $rules['options.*'] = 'required|string|max:1000';
            $rules['correct']   = 'required';
        }

        if ($type === 'picture_choice') {
            $rules['option_images']   = 'required|array|min:2';
            $rules['option_images.*'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
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
                'What is the capital of France?',
                'Choose the correct city from the options below.',
                'single_choice_radio',
                'General Knowledge',
                'Geography',
                'European Capitals',
                'easy',
                '1',
                'Paris has been the capital of France since the 5th century.',
                'London', 'Paris', 'Berlin', 'Madrid', '',
                '2', // Option 2 (Paris) is correct
                '', '', '', '', '', '', '', '', '', '', '', '', ''
            ],
            [
                'Which of the following are primary colors?',
                'Select all correct options.',
                'multiple_choice',
                'Science',
                'Physics',
                'Light and Color',
                'medium',
                '2',
                'Primary colors of light are Red, Green, and Blue. For pigments, they are Red, Yellow, and Blue.',
                'Red', 'Green', 'Yellow', 'Purple', '',
                '1,2,3', // Options 1, 2, and 3 are correct
                '', '', '', '', '', '', '', '', '', '', '', '', ''
            ],
            [
                'Water and Oxygen Chemical Formulas',
                'The chemical formula for water is ___ and oxygen is ___.',
                'fill_in_the_blanks',
                'Science',
                'Chemistry',
                'Molecules',
                'medium',
                '2',
                'Water is H2O and Oxygen gas is O2.',
                '', '', '', '', '', '',
                'H2O|O2', // Blank answers pipe-separated
                '', '', '', '', '', '', '', '', '', '', '', ''
            ],
            [
                'Match the country with its capital city.',
                'Match the items on the left with their correct pairs on the right.',
                'matching_text',
                'General Knowledge',
                'Geography',
                'Global Capitals',
                'easy',
                '2',
                'Japan - Tokyo, UK - London, France - Paris.',
                '', '', '', '', '', '', '',
                'Japan', 'Tokyo', 'United Kingdom', 'London', 'France', 'Paris', '', '', // matching pairs
                '', '', ''
            ],
            [
                'Write an essay explaining the causes of World War I.',
                'Provide details of the political tension, alliances, and the assassination in Sarajevo.',
                'free_text',
                'History',
                'World Wars',
                'WWI Causes',
                'hard',
                '5',
                'Grading should consider depth of alliance systems, militarism, imperialism, and nationalism analysis.',
                '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '',
                '500', // Word limit
                '', ''
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
     * Upload and parse the import file to count rows.
     */
    public function parseImportFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240', // max 10MB
        ]);

        $file = $request->file('file');
        
        // Generate unique token
        $token = \Illuminate\Support\Str::random(32);
        $tempDir = storage_path('app/temp_imports');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        
        $tempPath = $tempDir . '/import_' . $token . '.csv';
        $file->move($tempDir, 'import_' . $token . '.csv');

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

        $tempPath = storage_path('app/temp_imports/import_' . $token . '.csv');

        if (!file_exists($tempPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Import session expired or file not found.',
            ], 400);
        }

        $results = [
            'processed' => 0,
            'success_count' => 0,
            'failed_count' => 0,
            'errors' => [],
            'warnings' => [],
        ];

        if (($handle = fopen($tempPath, 'r')) !== false) {
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

                // Process subject, topic, subtopic
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
                            // Fallback to commas if no pipes are found
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
        if (($handle = fopen($tempPath, 'r')) !== false) {
            fgetcsv($handle); // skip header
            while (($row = fgetcsv($handle)) !== false) {
                if (!empty(array_filter($row))) {
                    $totalLines++;
                }
            }
            fclose($handle);
        }

        if ($offset + $limit >= $totalLines) {
            if (file_exists($tempPath)) {
                unlink($tempPath);
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


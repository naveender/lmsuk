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
}

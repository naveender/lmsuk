<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Paper;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Classes;
use App\Models\User;
use App\Models\YearGroup;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\DB;

class PaperForm extends Component
{
    // Mode
    public $paperId;
    public $isEdit = false;

    // Paper Fields
    public $type = 'test';
    public $title = '';
    public $instruction = ''; // Quill HTML binding
    public $subject_id = '';
    public $class_id = '';
    public $year_group_id = '';
    public $user_id = '';
    public $academic_year = '';
    public $difficulty = '';
    public $total_time = 0;
    public $default_marks = 1;
    public $question_pooling = false;

    // Advanced Config Fields
    public $allow_attempt_without_signup = false;
    public $allow_reattempt_question = false;
    public $display_result_question_by_question = false;
    public $allow_instant_feedback = false;
    public $hide_result = false;
    public $shuffle_questions = false;

    // Selected Questions (array of question IDs)
    public $selectedQuestionIds = [];

    // Filters for Questions Bank (Right Side)
    public $filterSubject = '';
    public $filterTopic = '';
    public $filterSubtopic = '';
    public $filterDifficulty = '';
    public $filterType = '';
    public $search = '';

    // Lists for cascading dropdowns in filter
    public $filterTopics = [];
    public $filterSubtopics = [];
    public $perPage = 15;

    protected $rules = [
        'type' => 'required|in:test,exam',
        'title' => 'required|string|max:255',
        'instruction' => 'nullable|string',
        'subject_id' => 'required|exists:subjects,id',
        'class_id' => 'required|exists:classes,id',
        'year_group_id' => 'required|exists:year_groups,id',
        'user_id' => 'required|exists:users,id',
        'academic_year' => 'required|string',
        'difficulty' => 'required|string',
        'total_time' => 'required|integer|min:1',
        'default_marks' => 'required|integer|min:1',
    ];

    public function mount($paper = null)
    {
        if ($paper) {
            $this->isEdit = true;
            $this->paperId = $paper->id;
            
            $this->type = $paper->type;
            $this->title = $paper->title;
            $this->instruction = $paper->instruction;
            $this->subject_id = $paper->subject_id;
            $this->class_id = $paper->class_id;
            $this->year_group_id = $paper->year_group_id;
            $this->user_id = $paper->user_id;
            $this->academic_year = $paper->academic_year;
            $this->difficulty = $paper->difficulty;
            $this->total_time = $paper->total_time;
            $this->default_marks = $paper->default_marks;
            $this->question_pooling = (bool)$paper->question_pooling;

            $this->allow_attempt_without_signup = (bool)$paper->allow_attempt_without_signup;
            $this->allow_reattempt_question = (bool)$paper->allow_reattempt_question;
            $this->display_result_question_by_question = (bool)$paper->display_result_question_by_question;
            $this->allow_instant_feedback = (bool)$paper->allow_instant_feedback;
            $this->hide_result = (bool)$paper->hide_result;
            $this->shuffle_questions = (bool)$paper->shuffle_questions;

            // Load selected question IDs
            $this->selectedQuestionIds = $paper->questions()->pluck('questions.id')->toArray();
        } else {
            // Set default creator if possible
            $this->user_id = auth()->id();
            // Try to set default academic year
            $this->academic_year = date('Y') . '-' . (date('Y') + 1);
        }
    }

    public function updatedFilterSubject($value)
    {
        $this->filterTopic = '';
        $this->filterSubtopic = '';
        $this->filterSubtopics = [];
        
        if ($value) {
            $this->filterTopics = Topic::where('subject_id', $value)
                ->where(function ($q) {
                    $q->whereNull('parent')->orWhere('parent', 0);
                })
                ->orderBy('name')
                ->get()
                ->toArray();
        } else {
            $this->filterTopics = [];
        }
    }

    public function updatedFilterTopic($value)
    {
        $this->filterSubtopic = '';
        
        if ($value) {
            $this->filterSubtopics = Topic::where('parent', $value)
                ->orderBy('name')
                ->get()
                ->toArray();
        } else {
            $this->filterSubtopics = [];
        }
    }

    public function addQuestion($id)
    {
        if (!in_array($id, $this->selectedQuestionIds)) {
            $this->selectedQuestionIds[] = $id;
        }
    }

    public function removeQuestion($id)
    {
        $this->selectedQuestionIds = array_values(array_filter($this->selectedQuestionIds, function($value) use ($id) {
            return $value != $id;
        }));
    }

    public function moveUp($index)
    {
        if ($index > 0 && isset($this->selectedQuestionIds[$index])) {
            $temp = $this->selectedQuestionIds[$index - 1];
            $this->selectedQuestionIds[$index - 1] = $this->selectedQuestionIds[$index];
            $this->selectedQuestionIds[$index] = $temp;
        }
    }

    public function moveDown($index)
    {
        if ($index < count($this->selectedQuestionIds) - 1 && isset($this->selectedQuestionIds[$index])) {
            $temp = $this->selectedQuestionIds[$index + 1];
            $this->selectedQuestionIds[$index + 1] = $this->selectedQuestionIds[$index];
            $this->selectedQuestionIds[$index] = $temp;
        }
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $data = [
                'type' => $this->type,
                'title' => $this->title,
                'instruction' => $this->instruction,
                'subject_id' => $this->subject_id,
                'class_id' => $this->class_id,
                'year_group_id' => $this->year_group_id,
                'user_id' => $this->user_id,
                'academic_year' => $this->academic_year,
                'difficulty' => $this->difficulty,
                'total_time' => $this->total_time,
                'default_marks' => $this->default_marks,
                'question_pooling' => (bool)$this->question_pooling,
                'allow_attempt_without_signup' => (bool)$this->allow_attempt_without_signup,
                'allow_reattempt_question' => (bool)$this->allow_reattempt_question,
                'display_result_question_by_question' => (bool)$this->display_result_question_by_question,
                'allow_instant_feedback' => (bool)$this->allow_instant_feedback,
                'hide_result' => (bool)$this->hide_result,
                'shuffle_questions' => (bool)$this->shuffle_questions,
            ];

            if ($this->isEdit) {
                $paper = Paper::findOrFail($this->paperId);
                $paper->update($data);
            } else {
                $paper = Paper::create($data);
            }

            // Sync questions with sort order and marks
            $syncData = [];
            foreach ($this->selectedQuestionIds as $index => $qId) {
                $syncData[$qId] = [
                    'sort_order' => $index,
                    'marks' => $this->default_marks
                ];
            }
            $paper->questions()->sync($syncData);

            DB::commit();

            session()->flash('success', $this->isEdit ? 'Paper updated successfully!' : 'Paper created successfully!');
            return redirect()->route('admin.papers.index');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error occurred: ' . $e->getMessage());
        }
    }

    public function updating($name, $value)
    {
        // If any filter property is being updated, reset pagination/perPage to 15
        if (in_array($name, ['filterSubject', 'filterTopic', 'filterSubtopic', 'filterDifficulty', 'filterType', 'search'])) {
            $this->perPage = 15;
        }
    }

    public function loadMore()
    {
        $this->perPage += 15;
    }

    public function resetFilters()
    {
        $this->filterSubject = '';
        $this->filterTopic = '';
        $this->filterSubtopic = '';
        $this->filterDifficulty = '';
        $this->filterType = '';
        $this->search = '';
        $this->perPage = 15;
        
        $this->filterTopics = [];
        $this->filterSubtopics = [];
    }

    public function render()
    {
        // Query questions matching right-hand side filters (eager load options for real-time preview)
        $qQuery = Question::query()->with(['subject', 'topic', 'subtopic', 'options'])->where('is_active', true);

        if ($this->filterSubject) {
            $qQuery->where('subject_id', $this->filterSubject);
        }
        if ($this->filterTopic) {
            $qQuery->where('topic_id', $this->filterTopic);
        }
        if ($this->filterSubtopic) {
            $qQuery->where('subtopic_id', $this->filterSubtopic);
        }
        if ($this->filterDifficulty) {
            $qQuery->where('difficulty', $this->filterDifficulty);
        }
        if ($this->filterType) {
            $qQuery->where('type', $this->filterType);
        }
        // Optimize: Only query wildcard text if search term is at least 3 characters
        if ($this->search && strlen($this->search) >= 3) {
            $qQuery->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Get matching questions with perPage pagination limit for maximum speed on large datasets
        $availableQuestions = $qQuery->latest()->limit($this->perPage)->get();

        // Get actual question details for selected questions in their exact sorted order
        $selectedQuestions = collect();
        if (!empty($this->selectedQuestionIds)) {
            $fetchedQuestions = Question::whereIn('id', $this->selectedQuestionIds)->get()->keyBy('id');
            foreach ($this->selectedQuestionIds as $qId) {
                if (isset($fetchedQuestions[$qId])) {
                    $selectedQuestions->push($fetchedQuestions[$qId]);
                }
            }
        }

        return view('livewire.admin.paper-form', [
            'availableQuestions' => $availableQuestions,
            'selectedQuestions' => $selectedQuestions,
            'subjects' => Subject::where('is_active', true)->orderBy('title')->get(),
            'classes' => Classes::where('is_active', true)->orderBy('name')->get(),
            'yearGroups' => YearGroup::where('is_active', true)->orderBy('title')->get(),
            'academicYears' => AcademicYear::where('is_active', true)->orderBy('name', 'desc')->get(),
            'tutors' => User::whereIn('role', ['admin', 'tutor'])->orderBy('name')->get(),
            'difficulties' => Paper::DIFFICULTIES,
            'questionTypes' => Question::TYPES,
        ]);
    }
}

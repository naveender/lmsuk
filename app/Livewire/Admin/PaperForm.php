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
use App\Models\Course;
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
    public $topic_id = '';
    public $subtopic_id = '';
    public $class_id = '';
    public $year_group_id = '';
    public $user_id = '';
    public $academic_year = '';
    public $topics = [];
    public $subtopics = [];
    public $difficulty = '';
    public $total_time = 0;
    public $default_marks = 1;
    public $question_pooling = false;

    // Course assignment fields
    public $course_id = '';
    public $week = 1;
    public $create_new_course = false;
    public $new_course_name = '';
    public $original_course_id = '';
    public $original_week_id = '';
    public $week_mode = 'existing'; // 'existing' or 'new'
    public $selected_week_id = '';
    public $new_week_name = '';
    public $new_week_due_date = '';
    public $courseWeeks = [];

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
        'type' => 'required|in:test,exam,quiz,homework',
        'title' => 'required|string|max:255',
        'instruction' => 'nullable|string',
        'subject_id' => 'required|exists:subjects,id',
        'topic_id' => 'nullable|exists:topics,id',
        'subtopic_id' => 'nullable|exists:topics,id',
        'class_id' => 'required|exists:classes,id',
        'year_group_id' => 'required|exists:year_groups,id',
        'user_id' => 'required|exists:users,id',
        'academic_year' => 'required|string',
        'difficulty' => 'required|string',
        'total_time' => 'required|integer|min:1',
        'default_marks' => 'required|integer|min:1',
        'course_id' => 'nullable|exists:courses,id',
        'week' => 'nullable',
        'new_course_name' => 'nullable|string|max:255',
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
            $this->topic_id = $paper->topic_id;
            $this->subtopic_id = $paper->subtopic_id;
            $this->class_id = $paper->class_id;
            $this->year_group_id = $paper->year_group_id;
            $this->user_id = $paper->user_id;
            $this->academic_year = $paper->academic_year;
            $this->difficulty = $paper->difficulty;
            $this->total_time = $paper->total_time;
            $this->default_marks = $paper->default_marks;
            $this->question_pooling = (bool)$paper->question_pooling;

            // Load topics & subtopics if editing
            if ($this->subject_id) {
                $this->topics = Topic::where('subject_id', $this->subject_id)
                    ->where(function ($q) {
                        $q->whereNull('parent')->orWhere('parent', 0);
                    })
                    ->orderBy('name')
                    ->get()
                    ->toArray();
            }
            if ($this->topic_id) {
                $this->subtopics = Topic::where('parent', $this->topic_id)
                    ->orderBy('name')
                    ->get()
                    ->toArray();
            }

            $this->allow_attempt_without_signup = (bool)$paper->allow_attempt_without_signup;
            $this->allow_reattempt_question = (bool)$paper->allow_reattempt_question;
            $this->display_result_question_by_question = (bool)$paper->display_result_question_by_question;
            $this->allow_instant_feedback = (bool)$paper->allow_instant_feedback;
            $this->hide_result = (bool)$paper->hide_result;
            $this->shuffle_questions = (bool)$paper->shuffle_questions;

            // Load selected question IDs
            $this->selectedQuestionIds = $paper->questions()->pluck('questions.id')->toArray();

            // Load course assignment if exists
            $firstCourse = $paper->courses()->first();
            if ($firstCourse) {
                $this->course_id = $firstCourse->id;
                $this->original_course_id = $firstCourse->id;
                $this->week = $firstCourse->pivot->week;
                $this->original_week_id = $firstCourse->pivot->week_id;
                $this->selected_week_id = $firstCourse->pivot->week_id;
                
                if ($this->course_id) {
                    $this->courseWeeks = \App\Models\Week::where('course_id', $this->course_id)
                        ->orderBy('name')
                        ->get()
                        ->toArray();
                }

                if ($this->selected_week_id) {
                    $this->week_mode = 'existing';
                } else {
                    $this->week_mode = 'new';
                }
            }
        } else {
            // Set default creator if possible
            $this->user_id = auth()->id();
            // Try to set default academic year
            $this->academic_year = date('Y') . '-' . (date('Y') + 1);
        }
    }

    public function updatedCourseId($value)
    {
        $this->selected_week_id = '';
        $this->courseWeeks = [];
        if ($value) {
            $this->courseWeeks = \App\Models\Week::where('course_id', $value)
                ->orderBy('name')
                ->get()
                ->toArray();
            
            if (empty($this->courseWeeks)) {
                $this->week_mode = 'new';
            } else {
                $this->week_mode = 'existing';
            }
        }
    }

    public function updatedCreateNewCourse($value)
    {
        if ($value) {
            $this->week_mode = 'new';
            $this->course_id = '';
            $this->selected_week_id = '';
            $this->courseWeeks = [];
        }
    }

    public function updatedSubjectId($value)
    {
        $this->topic_id = '';
        $this->subtopic_id = '';
        $this->subtopics = [];
        
        if ($value) {
            $this->topics = Topic::where('subject_id', $value)
                ->where(function ($q) {
                    $q->whereNull('parent')->orWhere('parent', 0);
                })
                ->orderBy('name')
                ->get()
                ->toArray();
        } else {
            $this->topics = [];
        }
    }

    public function updatedTopicId($value)
    {
        $this->subtopic_id = '';
        
        if ($value) {
            $this->subtopics = Topic::where('parent', $value)
                ->orderBy('name')
                ->get()
                ->toArray();
        } else {
            $this->subtopics = [];
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

        if ($this->create_new_course) {
            $this->validate([
                'new_course_name' => 'required|string|max:255',
            ]);
        }

        if ($this->create_new_course || $this->course_id) {
            if ($this->week_mode === 'existing') {
                $this->validate([
                    'selected_week_id' => 'required|exists:weeks,id',
                ]);
            } else {
                $this->validate([
                    'new_week_name' => 'required|string|max:255',
                    'new_week_due_date' => 'nullable|date',
                ]);
            }
        }

        DB::beginTransaction();
        try {
            $data = [
                'type' => $this->type,
                'title' => $this->title,
                'instruction' => $this->instruction,
                'subject_id' => $this->subject_id,
                'topic_id' => $this->topic_id ?: null,
                'subtopic_id' => $this->subtopic_id ?: null,
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

            // Handle Course & Week Assignment
            $courseIdToAssign = null;
            if ($this->create_new_course && !empty($this->new_course_name)) {
                $newCourse = Course::create([
                    'name' => $this->new_course_name,
                    'is_active' => true,
                ]);
                $this->course_id = $newCourse->id;
                $courseIdToAssign = $newCourse->id;
            } elseif ($this->course_id) {
                $courseIdToAssign = $this->course_id;
            }

            if ($courseIdToAssign) {
                $weekId = null;
                $weekName = '';
                if ($this->week_mode === 'existing' && $this->selected_week_id) {
                    $weekId = $this->selected_week_id;
                    $weekModel = \App\Models\Week::find($weekId);
                    $weekName = $weekModel ? $weekModel->name : '';
                } elseif ($this->week_mode === 'new' && !empty($this->new_week_name)) {
                    $weekModel = \App\Models\Week::create([
                        'course_id' => $courseIdToAssign,
                        'name' => $this->new_week_name,
                        'due_date' => $this->new_week_due_date ?: null,
                    ]);
                    $weekId = $weekModel->id;
                    $weekName = $weekModel->name;
                }

                if ($weekId) {
                    $weekNumber = 1;
                    if (preg_match('/(\d+)/', $weekName, $matches)) {
                        $weekNumber = (int) $matches[1];
                    }

                    if ($this->isEdit && $this->original_course_id && $this->original_course_id != $courseIdToAssign) {
                        $paper->courses()->detach($this->original_course_id);
                    }
                    
                    $paper->courses()->syncWithoutDetaching([
                        $courseIdToAssign => [
                            'week' => $weekNumber,
                            'week_id' => $weekId
                        ]
                    ]);

                    $paper->courses()->updateExistingPivot($courseIdToAssign, [
                        'week' => $weekNumber,
                        'week_id' => $weekId
                    ]);
                }
            } elseif ($this->isEdit && $this->original_course_id) {
                $paper->courses()->detach($this->original_course_id);
            }

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
            'coursesList' => Course::where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}

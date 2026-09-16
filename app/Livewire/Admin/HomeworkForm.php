<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Homework;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Classes;
use App\Models\YearGroup;
use App\Models\AcademicYear;
use App\Models\User;
use App\Models\Course;
use App\Models\Week;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HomeworkForm extends Component
{
    use WithFileUploads;

    // Mode
    public $homeworkId;
    public $isEdit = false;

    // Basic Homework Fields
    public $title = '';
    public $description = ''; // Quill editor binding
    public $subject_id = '';
    public $topic_id = '';
    public $subtopic_id = '';
    public $class_id = '';
    public $year_group_id = '';
    public $academic_year = '';
    public $user_id = '';
    public $is_active = true;
    public $topics = [];
    public $subtopics = [];

    // File Upload Fields
    public $file;
    public $existing_file_name = '';
    public $existing_file_path = '';
    public $existing_file_size = '';
    public $existing_file_type = '';

    // Course assignment fields (matching PaperForm)
    public $course_id = '';
    public $create_new_course = false;
    public $new_course_name = '';
    public $original_course_id = '';
    public $original_week_id = '';
    public $week_mode = 'existing'; // 'existing' or 'new'
    public $selected_week_id = '';
    public $new_week_name = '';
    public $new_week_due_date = '';
    public $courseWeeks = [];

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'topic_id' => 'nullable|exists:topics,id',
            'subtopic_id' => 'nullable|exists:topics,id',
            'class_id' => 'required|exists:classes,id',
            'year_group_id' => 'required|exists:year_groups,id',
            'user_id' => 'required|exists:users,id',
            'academic_year' => 'required|string',
            'is_active' => 'boolean',
            'file' => 'nullable|file|mimes:docx,doc,pdf,txt|max:25600',
            'course_id' => 'nullable|exists:courses,id',
            'new_course_name' => 'nullable|string|max:255',
        ];
    }

    public function mount($homework = null)
    {
        if ($homework) {
            $this->isEdit = true;
            $this->homeworkId = $homework->id;

            $this->title = $homework->title;
            $this->description = $homework->description;
            $this->subject_id = $homework->subject_id;
            $this->topic_id = $homework->topic_id;
            $this->subtopic_id = $homework->subtopic_id;
            $this->class_id = $homework->class_id;
            $this->year_group_id = $homework->year_group_id;
            $this->user_id = $homework->user_id;
            $this->academic_year = $homework->academic_year;
            $this->is_active = (bool)$homework->is_active;

            $this->existing_file_name = $homework->file_name;
            $this->existing_file_path = $homework->file_path;
            $this->existing_file_size = $homework->file_size;
            $this->existing_file_type = $homework->file_type;

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

            // Load course assignment if exists
            $firstCourse = $homework->courses()->first();
            if ($firstCourse) {
                $this->course_id = $firstCourse->id;
                $this->original_course_id = $firstCourse->id;
                $this->original_week_id = $firstCourse->pivot->week_id;
                $this->selected_week_id = $firstCourse->pivot->week_id;

                if ($this->course_id) {
                    $this->courseWeeks = Week::where('course_id', $this->course_id)
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
            $this->user_id = auth()->id();
            $this->academic_year = date('Y') . '-' . (date('Y') + 1);
        }
    }

    public function updatedCourseId($value)
    {
        $this->selected_week_id = '';
        $this->courseWeeks = [];
        if ($value) {
            $this->courseWeeks = Week::where('course_id', $value)
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

    public function removeExistingFile()
    {
        $this->existing_file_name = '';
        $this->existing_file_path = '';
        $this->existing_file_size = '';
        $this->existing_file_type = '';
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
            $filePath = $this->existing_file_path;
            $fileName = $this->existing_file_name;
            $fileType = $this->existing_file_type;
            $fileSize = $this->existing_file_size;

            if ($this->file) {
                // Remove previous file if replacing
                if ($this->existing_file_path && Storage::disk('public')->exists($this->existing_file_path)) {
                    Storage::disk('public')->delete($this->existing_file_path);
                }

                $originalName = $this->file->getClientOriginalName();
                $extension = strtolower($this->file->getClientOriginalExtension());
                $bytes = $this->file->getSize();

                // Format human-readable size
                if ($bytes >= 1048576) {
                    $formattedSize = number_format($bytes / 1048576, 2) . ' MB';
                } elseif ($bytes >= 1024) {
                    $formattedSize = number_format($bytes / 1024, 1) . ' KB';
                } else {
                    $formattedSize = $bytes . ' B';
                }

                $storedPath = $this->file->store('homeworks', 'public');

                $filePath = $storedPath;
                $fileName = $originalName;
                $fileType = $extension;
                $fileSize = $formattedSize;
            }

            $data = [
                'title' => $this->title,
                'description' => $this->description,
                'subject_id' => $this->subject_id,
                'topic_id' => $this->topic_id ?: null,
                'subtopic_id' => $this->subtopic_id ?: null,
                'class_id' => $this->class_id,
                'year_group_id' => $this->year_group_id,
                'user_id' => $this->user_id,
                'academic_year' => $this->academic_year,
                'is_active' => $this->is_active ? 1 : 0,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'file_type' => $fileType,
                'file_size' => $fileSize,
            ];

            if ($this->isEdit) {
                $homework = Homework::findOrFail($this->homeworkId);
                $homework->update($data);
            } else {
                $homework = Homework::create($data);
            }

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
                    $weekModel = Week::find($weekId);
                    $weekName = $weekModel ? $weekModel->name : '';
                } elseif ($this->week_mode === 'new' && !empty($this->new_week_name)) {
                    $weekModel = Week::create([
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
                        $homework->courses()->detach($this->original_course_id);
                    }

                    $homework->courses()->syncWithoutDetaching([
                        $courseIdToAssign => [
                            'week' => $weekNumber,
                            'week_id' => $weekId
                        ]
                    ]);

                    $homework->courses()->updateExistingPivot($courseIdToAssign, [
                        'week' => $weekNumber,
                        'week_id' => $weekId
                    ]);
                }
            } elseif ($this->isEdit && $this->original_course_id) {
                $homework->courses()->detach($this->original_course_id);
            }

            DB::commit();

            session()->flash('success', $this->isEdit ? 'Homework updated successfully!' : 'Homework created successfully!');
            return redirect()->route('admin.homeworks.index');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error occurred: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.homework-form', [
            'subjects' => Subject::where('is_active', true)->orderBy('title')->get(),
            'classes' => Classes::where('is_active', true)->orderBy('name')->get(),
            'yearGroups' => YearGroup::where('is_active', true)->orderBy('title')->get(),
            'academicYears' => AcademicYear::where('is_active', true)->orderBy('name', 'desc')->get(),
            'tutors' => User::whereIn('role', ['admin', 'tutor'])->orderBy('name')->get(),
            'coursesList' => Course::where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}

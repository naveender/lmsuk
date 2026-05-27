<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paper extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'title',
        'instruction',
        'subject_id',
        'topic_id',
        'subtopic_id',
        'class_id',
        'year_group_id',
        'user_id',
        'academic_year',
        'difficulty',
        'total_time',
        'default_marks',
        'question_pooling',
        'allow_attempt_without_signup',
        'allow_reattempt_question',
        'display_result_question_by_question',
        'allow_instant_feedback',
        'hide_result',
        'shuffle_questions',
    ];

    protected $casts = [
        'question_pooling' => 'boolean',
        'allow_attempt_without_signup' => 'boolean',
        'allow_reattempt_question' => 'boolean',
        'display_result_question_by_question' => 'boolean',
        'allow_instant_feedback' => 'boolean',
        'hide_result' => 'boolean',
        'shuffle_questions' => 'boolean',
        'total_time' => 'integer',
        'default_marks' => 'integer',
    ];

    public const TYPES = [
        'test' => 'Test',
        'exam' => 'Exam',
    ];

    public const DIFFICULTIES = [
        'easy'   => 'Easy',
        'medium' => 'Medium',
        'hard'   => 'Hard',
    ];

    /**
     * Get the subject that this paper belongs to.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the topic that this paper belongs to.
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    /**
     * Get the subtopic that this paper belongs to.
     */
    public function subtopic()
    {
        return $this->belongsTo(Topic::class, 'subtopic_id');
    }

    /**
     * Get the class that this paper belongs to.
     */
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    /**
     * Get the year group associated with this paper.
     */
    public function yearGroup()
    {
        return $this->belongsTo(YearGroup::class, 'year_group_id');
    }

    /**
     * Get the user (admin/tutor) who created this paper.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The questions associated with this paper.
     */
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'paper_question')
            ->withPivot('sort_order', 'marks')
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }

    /**
     * Get the assignments associated with this paper.
     */
    public function assignments()
    {
        return $this->hasMany(PaperAssignment::class);
    }

    /**
     * Scope a query to only include papers visible to the student.
     */
    public function scopeVisibleTo($query, User $student)
    {
        return $query->whereHas('assignments', function ($q) use ($student) {
            $q->where(function ($sub) use ($student) {
                // Case 1: Student (All or Specific)
                $sub->where('assign_type', 'students')
                    ->where(function ($s) use ($student) {
                        $s->where('assign_mode', 'all')
                          ->orWhere('target_id', $student->id);
                    });
            })->orWhere(function ($sub) use ($student) {
                // Case 2: Class (All class students or specific class)
                $sub->where('assign_type', 'classes')
                    ->where(function ($s) use ($student) {
                        $s->where('assign_mode', 'all')
                          ->whereExists(function ($existsQuery) use ($student) {
                              $existsQuery->select(\Illuminate\Support\Facades\DB::raw(1))
                                  ->from('class_student')
                                  ->whereColumn('class_student.user_id', \Illuminate\Support\Facades\DB::raw($student->id));
                          })
                          ->orWhereIn('target_id', $student->classes()->pluck('classes.id'));
                    });
            })->orWhere(function ($sub) use ($student) {
                // Case 3: Session (All session students or specific session)
                $sub->where('assign_type', 'sessions')
                    ->where(function ($s) use ($student) {
                        $s->where('assign_mode', 'all')
                          ->whereExists(function ($existsQuery) use ($student) {
                              $existsQuery->select(\Illuminate\Support\Facades\DB::raw(1))
                                  ->from('student_details')
                                  ->whereColumn('student_details.user_id', \Illuminate\Support\Facades\DB::raw($student->id))
                                  ->whereNotNull('student_details.academic_year');
                          })
                          ->orWhere('target_id', $student->studentDetail?->academic_year);
                    });
            })->orWhere(function ($sub) use ($student) {
                // Case 4: Group Year (All group year students or specific group year)
                $sub->where('assign_type', 'group_years')
                    ->where(function ($s) use ($student) {
                        $s->where('assign_mode', 'all')
                          ->whereExists(function ($existsQuery) use ($student) {
                              $existsQuery->select(\Illuminate\Support\Facades\DB::raw(1))
                                  ->from('student_details')
                                  ->whereColumn('student_details.user_id', \Illuminate\Support\Facades\DB::raw($student->id))
                                  ->whereNotNull('student_details.group_year');
                          })
                          ->orWhere('target_value', $student->studentDetail?->group_year);
                    });
            });
        });
    }
}

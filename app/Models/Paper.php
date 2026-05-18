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
        'class_id',
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
     * Get the class that this paper belongs to.
     */
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
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
}

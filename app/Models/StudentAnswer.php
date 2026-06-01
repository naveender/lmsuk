<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    protected $fillable = [
        'paper_attempt_id',
        'question_id',
        'answer_text',
        'selected_option_id',
        'selected_options',
        'is_correct',
        'marks_obtained',
        'time_spent',
        'is_flagged',
        'confidence',
    ];

    protected $casts = [
        'selected_options' => 'array',
        'is_correct' => 'boolean',
        'time_spent' => 'integer',
        'is_flagged' => 'boolean',
    ];

    public function attempt()
    {
        return $this->belongsTo(PaperAttempt::class, 'paper_attempt_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function selectedOption()
    {
        return $this->belongsTo(QuestionOption::class, 'selected_option_id');
    }
}

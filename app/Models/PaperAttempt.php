<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaperAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'paper_id',
        'status',
        'time_spent',
        'score',
        'max_score',
        'correct_answers',
        'total_questions',
        'started_at',
        'paused_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'paused_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }

    public function answers()
    {
        return $this->hasMany(StudentAnswer::class, 'paper_attempt_id');
    }
}

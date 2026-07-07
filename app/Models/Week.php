<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    protected $fillable = [
        'course_id',
        'name',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    /**
     * Get the course associated with this week.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the papers assigned to this week.
     */
    public function papers()
    {
        return $this->belongsToMany(Paper::class, 'course_paper', 'week_id', 'paper_id')
            ->withPivot('course_id', 'week')
            ->withTimestamps();
    }
}

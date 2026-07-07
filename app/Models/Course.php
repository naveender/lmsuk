<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the weeks associated with the course.
     */
    public function weeks()
    {
        return $this->hasMany(Week::class)->orderBy('name');
    }

    /**
     * Get the papers associated with the course.
     */
    public function papers()
    {
        return $this->belongsToMany(Paper::class, 'course_paper')
            ->withPivot('week', 'week_id')
            ->withTimestamps()
            ->orderByPivot('week');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classes extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'group_year',
        'academic_year',
        'description',
        'is_active',
    ];

    public function students()
    {
        return $this->belongsToMany(User::class, 'class_student', 'class_id', 'user_id')->withTimestamps();
    }
}

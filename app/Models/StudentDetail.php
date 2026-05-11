<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class StudentDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'date_of_birth',
        'group_year',
        'academic_year',
        'region',
        'student_phone',
        'student_email',
        'gender',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

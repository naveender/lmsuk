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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class YearGroup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'value',
        'description',
        'is_active',
    ];
}

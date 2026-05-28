<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'is_active',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}

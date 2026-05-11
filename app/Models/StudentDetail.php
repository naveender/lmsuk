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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

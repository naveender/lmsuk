<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class ParentDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'relation',
        'alternate_phone',
        'emergency_contact',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

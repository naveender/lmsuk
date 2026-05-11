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

    /**
     * Get all students linked to this parent through the user model.
     */
    public function students()
    {
        return $this->hasMany(StudentDetail::class, 'parent_id', 'user_id');
    }
}

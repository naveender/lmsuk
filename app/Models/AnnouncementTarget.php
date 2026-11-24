<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementTarget extends Model
{
    protected $fillable = [
        'announcement_id',
        'target_type',
        'target_id',
    ];

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    public function classModel()
    {
        return $this->belongsTo(Classes::class, 'target_id');
    }

    public function yearGroup()
    {
        return $this->belongsTo(YearGroup::class, 'target_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'target_id');
    }
}

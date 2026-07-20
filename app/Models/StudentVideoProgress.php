<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentVideoProgress extends Model
{
    protected $table = 'student_video_progress';

    protected $fillable = [
        'user_id',
        'media_file_id',
        'watch_time',
        'last_position',
        'is_completed',
    ];

    /**
     * Get the User/Student relationship.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Media File relationship.
     */
    public function mediaFile()
    {
        return $this->belongsTo(MediaFile::class);
    }
}

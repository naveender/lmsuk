<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementAuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'announcement_id',
        'user_id',
        'action',
        'details',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    public static function log($announcementId, $action, $details = null)
    {
        return self::create([
            'announcement_id' => $announcementId,
            'user_id' => auth()->id(),
            'action' => $action,
            'details' => $details,
        ]);
    }
}

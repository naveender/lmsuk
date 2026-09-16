<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Homework extends Model
{
    use SoftDeletes;

    protected $table = 'homeworks';

    protected $fillable = [
        'title',
        'description',
        'subject_id',
        'topic_id',
        'subtopic_id',
        'class_id',
        'year_group_id',
        'academic_year',
        'user_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Subject relation
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Topic relation
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    /**
     * Subtopic relation
     */
    public function subtopic()
    {
        return $this->belongsTo(Topic::class, 'subtopic_id');
    }

    /**
     * Class relation
     */
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    /**
     * Year Group relation
     */
    public function yearGroup()
    {
        return $this->belongsTo(YearGroup::class, 'year_group_id');
    }

    /**
     * Creator (Admin / Tutor) relation
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Associated courses
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_homework')
            ->withPivot('week', 'week_id')
            ->withTimestamps();
    }

    /**
     * Associated weeks
     */
    public function weeks()
    {
        return $this->belongsToMany(Week::class, 'course_homework', 'homework_id', 'week_id')
            ->withPivot('course_id', 'week')
            ->withTimestamps();
    }

    /**
     * Get the public URL to the uploaded file if available.
     */
    public function getFileUrlAttribute(): ?string
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            return Storage::disk('public')->url($this->file_path);
        }
        return null;
    }

    /**
     * Get an appropriate Lucide or Feather icon class based on file extension.
     */
    public function getFileIconAttribute(): string
    {
        $ext = strtolower($this->file_type ?? pathinfo($this->file_name ?? '', PATHINFO_EXTENSION));
        return match ($ext) {
            'pdf' => 'file-text',
            'doc', 'docx' => 'file',
            'txt' => 'align-left',
            default => 'paperclip',
        };
    }

    /**
     * Get human readable formatted file size safely handling string or numeric representations.
     */
    public function getFormattedFileSizeAttribute(): ?string
    {
        if (!$this->file_size) {
            return null;
        }

        if (is_numeric($this->file_size)) {
            $bytes = (float) $this->file_size;
            if ($bytes >= 1048576) {
                return number_format($bytes / 1048576, 1) . ' MB';
            } elseif ($bytes >= 1024) {
                return number_format($bytes / 1024, 1) . ' KB';
            }
            return $bytes . ' B';
        }

        return (string) $this->file_size;
    }
}

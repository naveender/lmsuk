<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MediaFile extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'path',
        'storage_disk',
        'original_name',
        'file_size',
        'mime_type',
        'status',
        'upload_id',
        'metadata',
        'subject_id',
        'class_id',
        'year_group_id',
        'academic_year',
        'duration',
        'thumbnail_path',
        'publication_status',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the Subject relationship.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the Class relationship.
     */
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    /**
     * Get the Year Group relationship.
     */
    public function yearGroup()
    {
        return $this->belongsTo(YearGroup::class, 'year_group_id');
    }

    /**
     * Get the courses assigned to this media file.
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_media_file', 'media_file_id', 'course_id')
            ->withPivot('week', 'week_id')
            ->withTimestamps();
    }

    /**
     * Get the public URL of the media thumbnail.
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            return Storage::disk('public')->url($this->thumbnail_path);
        }

        if ($this->type === 'youtube') {
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $this->path, $match);
            $ytId = $match[1] ?? null;
            return $ytId ? "https://img.youtube.com/vi/{$ytId}/hqdefault.jpg" : asset('theme/app-assets/images/pages/graphic-2.png');
        }

        // Return a default beautiful gradient placeholder
        return null;
    }

    /**
     * Get the public URL of the media asset.
     */
    public function getUrlAttribute()
    {
        if (in_array($this->type, ['video_file', 's3', 'wasabi'])) {
            if (!$this->path) {
                return null;
            }
            $disk = $this->storage_disk ?: 'public';
            return Storage::disk($disk)->url($this->path);
        }

        if ($this->type === 'video_url') {
            return $this->path;
        }

        if ($this->type === 'youtube') {
            // Check if path is already embed code or direct URL
            if (str_contains($this->path, 'youtube.com') || str_contains($this->path, 'youtu.be')) {
                return $this->path;
            }
            return "https://www.youtube.com/watch?v=" . $this->path;
        }

        if ($this->type === 'vimeo') {
            if (str_contains($this->path, 'vimeo.com')) {
                return $this->path;
            }
            return "https://vimeo.com/" . $this->path;
        }

        if ($this->type === 'google_drive') {
            return $this->path;
        }

        return $this->path;
    }

    /**
     * Get the HTML embed code or player URL.
     */
    public function getEmbedUrlAttribute()
    {
        if ($this->type === 'youtube') {
            $id = $this->extractYoutubeId($this->path);
            return $id ? "https://www.youtube.com/embed/" . $id : $this->path;
        }

        if ($this->type === 'vimeo') {
            $id = $this->extractVimeoId($this->path);
            return $id ? "https://player.vimeo.com/video/" . $id : $this->path;
        }

        if ($this->type === 'google_drive') {
            $id = $this->extractGoogleDriveId($this->path);
            return $id ? "https://drive.google.com/file/d/{$id}/preview" : $this->path;
        }

        if ($this->type === 'iframe') {
            return $this->path; // returns iframe code directly
        }

        return $this->url;
    }

    private function extractYoutubeId($url)
    {
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
            return $match[1];
        }
        return $url;
    }

    private function extractVimeoId($url)
    {
        if (preg_match('%vimeo\.com/(?:channels/(?:\w+\/)?|groups/([^/]*)/videos/|album/(\d+)/video/|video/|)(\d+)(?:$|/|\?)%i', $url, $match)) {
            return $match[3];
        }
        return $url;
    }

    private function extractGoogleDriveId($url)
    {
        if (preg_match('/src=".*?\/file\/d\/(.*?)\/preview"/', $url, $match)) {
            return $match[1];
        }
        if (preg_match('/\/file\/d\/(.*?)\//', $url, $match)) {
            return $match[1];
        }
        if (preg_match('/id=(.*?)($|&)/', $url, $match)) {
            return $match[1];
        }
        return $url;
    }
}

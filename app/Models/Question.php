<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'subject_id',
        'topic_id',
        'subtopic_id',
        'difficulty',
        'marks',
        'explanation',
        'explanation_images',
        'metadata',
        'image',
        'images',
        'is_active',
    ];

    protected $casts = [
        'metadata'           => 'array',
        'images'             => 'array',
        'explanation_images' => 'array',
        'is_active'          => 'boolean',
    ];

    public const TYPES = [
        'single_choice_radio'    => 'Single Choice – Radio',
        'single_choice_dropdown' => 'Single Choice – Dropdown',
        'multiple_choice'        => 'Multiple Choice',
        'picture_choice'         => 'Picture Choice',
        'fill_in_the_blanks'     => 'Fill in the Blanks',
        'matching_drag_drop'     => 'Matching (Drag & Drop)',
        'matching_text'          => 'Matching Text',
        'free_text'              => 'Free Text (Essay)',
        'file_upload'            => 'File Upload',
    ];

    public const DIFFICULTIES = [
        'easy'   => 'Easy',
        'medium' => 'Medium',
        'hard'   => 'Hard',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    public function subtopic()
    {
        return $this->belongsTo(Topic::class, 'subtopic_id');
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class)->orderBy('sort_order');
    }

    /**
     * Check if this question type uses options (choice-based).
     */
    public function usesOptions(): bool
    {
        return in_array($this->type, [
            'single_choice_radio',
            'single_choice_dropdown',
            'multiple_choice',
            'picture_choice',
        ]);
    }

    /**
     * Get the human-readable type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}

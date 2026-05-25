<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
   protected $fillable = [
        'code',
        'name',
        'parent',
        'slug',
        'date_added',
        'last_modified',
        'thumbnail',
        'sub_category_thumbnail',
        'subject_id',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function parentTopic()
    {
        return $this->belongsTo(Topic::class, 'parent');
    }

    public function subTopics()
    {
        return $this->hasMany(Topic::class, 'parent');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($topic) {
            $topic->subTopics()->delete();
        });
    }


    // Get all topics
    // $topics = Category::whereNull('parent')->get();

    // // Get subtopics of a topic
    // $subtopics = Category::where('parent', $topicId)->get();

    // // With relationship
    // $topics = Category::with('subCategories')->whereNull('parent')->get();
}

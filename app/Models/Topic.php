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
        'sub_category_thumbnail'
    ];

    public function parentTopic()
    {
        return $this->belongsTo(Topic::class, 'parent');
    }

    public function subTopics()
    {
        return $this->hasMany(Topic::class, 'parent');
    }


    // Get all topics
    // $topics = Category::whereNull('parent')->get();

    // // Get subtopics of a topic
    // $subtopics = Category::where('parent', $topicId)->get();

    // // With relationship
    // $topics = Category::with('subCategories')->whereNull('parent')->get();
}

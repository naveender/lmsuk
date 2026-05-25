<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\Subject;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function index(Request $request)
    {
        $query = Topic::query();

        // Base condition: only parent topics
        $query->where(function($q) {
            $q->whereNull('parent')->orWhere('parent', 0);
        });

        // Filter by created date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $filterBy = $request->input('filter_by', 'topic'); // Default to topic

            if ($filterBy == 'topic') {
                $query->where('name', 'like', '%' . $search . '%');
                $query->with('subtopics');
            } elseif ($filterBy == 'subtopic') {
                // Topic must have at least one subtopic matching the search
                $query->whereHas('subtopics', function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });

                // Also only load subtopics that match the search
                $query->with(['subtopics' => function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                }]);
            }
        } else {
            // Eager load all subtopics
            $query->with('subtopics');
        }

        $topics = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
            
        return view('admin.topics.index', compact('topics'));
    }

    public function add()
    {
        $topics = Topic::whereNull('parent')->orWhere('parent', 0)->get();
        $subjects = Subject::where('is_active', true)->orderBy('title')->get();
        return view('admin.topics.add', compact('topics', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'topic_code' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|integer',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $slug = \Illuminate\Support\Str::slug($request->name);
        
        // Ensure slug is unique
        $originalSlug = $slug;
        $count = 1;
        while (Topic::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('topics', 'public');
        }

        Topic::create([
            'code' => $request->topic_code,
            'name' => $request->name,
            'parent' => $request->parent_id == 0 ? null : $request->parent_id,
            'subject_id' => $request->subject_id,
            'slug' => $slug,
            'thumbnail' => $thumbnailPath,
        ]);

        return redirect()->route('topics')->with('success', 'Topic created successfully.');
    }

    public function edit(Topic $topic)
    {
        $categories = Topic::whereNull('parent')->orWhere('parent', 0)->where('id', '!=', $topic->id)->get();
        $subjects = Subject::where('is_active', true)->orderBy('title')->get();
        return view('admin.topics.edit', compact('topic', 'categories', 'subjects'));
    }

    public function update(Request $request, Topic $topic)
    {
        $request->validate([
            'topic_code' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|integer',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $thumbnailPath = $topic->thumbnail;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('topics', 'public');
        }

        $slug = $topic->slug;
        if ($request->name !== $topic->name) {
            $slug = \Illuminate\Support\Str::slug($request->name);
            $originalSlug = $slug;
            $count = 1;
            while (Topic::where('slug', $slug)->where('id', '!=', $topic->id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
        }

        $topic->update([
            'code' => $request->topic_code,
            'name' => $request->name,
            'parent' => $request->parent_id == 0 ? null : $request->parent_id,
            'subject_id' => $request->subject_id,
            'slug' => $slug,
            'thumbnail' => $thumbnailPath,
        ]);

        return redirect()->route('topics')->with('success', 'Topic updated successfully.');
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();
        return redirect()->route('topics')->with('success', 'Topic deleted successfully.');
    }
}

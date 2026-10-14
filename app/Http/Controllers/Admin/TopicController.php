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

    public function tree(Topic $topic)
    {
        $topic->load(['subject', 'subTopics' => function($q) {
            $q->with(['subTopics' => function($sq) {
                $sq->with('subTopics')->orderBy('name', 'asc');
            }])->orderBy('name', 'asc');
        }]);

        return response()->json([
            'success' => true,
            'topic' => [
                'id' => $topic->id,
                'name' => $topic->name,
                'code' => $topic->code,
                'subject_id' => $topic->subject_id,
                'subject' => $topic->subject ? $topic->subject->title : null,
                'subtopics_count' => $this->countAllSubtopics($topic),
                'subtopics' => $topic->subTopics->map(function($sub) {
                    return $this->formatTopicNode($sub);
                }),
            ]
        ]);
    }

    private function countAllSubtopics($topic)
    {
        $count = $topic->subTopics->count();
        foreach ($topic->subTopics as $child) {
            if ($child->subTopics && $child->subTopics->isNotEmpty()) {
                $count += $this->countAllSubtopics($child);
            }
        }
        return $count;
    }

    private function formatTopicNode($node)
    {
        return [
            'id' => $node->id,
            'name' => $node->name,
            'code' => $node->code,
            'parent' => $node->parent,
            'subject_id' => $node->subject_id,
            'created_at' => $node->created_at ? $node->created_at->format('d M Y') : 'N/A',
            'children' => $node->subTopics ? $node->subTopics->map(function($c) {
                return $this->formatTopicNode($c);
            }) : [],
        ];
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

        $parentId = $request->parent_id == 0 ? null : $request->parent_id;
        $subjectId = $request->subject_id;
        if (!$subjectId && $parentId) {
            $parentTopic = Topic::find($parentId);
            if ($parentTopic) {
                $subjectId = $parentTopic->subject_id;
            }
        }

        $newTopic = Topic::create([
            'code' => $request->topic_code,
            'name' => $request->name,
            'parent' => $parentId,
            'subject_id' => $subjectId,
            'slug' => $slug,
            'thumbnail' => $thumbnailPath,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Subtopic created successfully.',
                'topic' => $this->formatTopicNode($newTopic)
            ]);
        }

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

        $parentId = $request->has('parent_id') ? ($request->parent_id == 0 ? null : $request->parent_id) : $topic->parent;
        $subjectId = $request->has('subject_id') ? $request->subject_id : $topic->subject_id;

        $topic->update([
            'code' => $request->topic_code ?? $topic->code,
            'name' => $request->name,
            'parent' => $parentId,
            'subject_id' => $subjectId,
            'slug' => $slug,
            'thumbnail' => $thumbnailPath,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Subtopic updated successfully.',
                'topic' => $this->formatTopicNode($topic)
            ]);
        }

        return redirect()->route('topics')->with('success', 'Topic updated successfully.');
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();

        if ($request->ajax() || $request->wantsJson() || request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Topic deleted successfully.'
            ]);
        }

        return redirect()->route('topics')->with('success', 'Topic deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::query();
        
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $announcements = $query->latest()->paginate(10)->appends($request->all());
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|integer|in:1,2,3',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:10240',
            'description' => 'nullable|string',
        ]);

        $data = $request->except('media');
        $data['status'] = $request->has('status') ? 1 : 0;

        if ($request->hasFile('media')) {
            $data['media'] = $request->file('media')->store('announcements', 'public');
        }

        Announcement::create($data);

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement created successfully.');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'type' => 'required|integer|in:1,2,3',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:10240',
            'description' => 'nullable|string',
        ]);

        $data = $request->except('media');
        $data['status'] = $request->has('status') ? 1 : 0;

        if ($request->hasFile('media')) {
            if ($announcement->media && Storage::disk('public')->exists($announcement->media)) {
                Storage::disk('public')->delete($announcement->media);
            }
            $data['media'] = $request->file('media')->store('announcements', 'public');
        }

        $announcement->update($data);

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->media && Storage::disk('public')->exists($announcement->media)) {
            Storage::disk('public')->delete($announcement->media);
        }
        $announcement->delete();

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paper;
use App\Models\Subject;
use App\Models\Classes;
use Illuminate\Http\Request;

class PaperController extends Controller
{
    /**
     * Display a listing of exam papers.
     */
    public function index(Request $request)
    {
        $query = Paper::with(['subject', 'class', 'user']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        $papers = $query->latest()->paginate(10)->withQueryString();
        
        $subjects = Subject::where('is_active', true)->orderBy('title')->get();
        $classes = Classes::where('is_active', true)->orderBy('name')->get();

        return view('admin.papers.index', compact('papers', 'subjects', 'classes'));
    }

    /**
     * Show the form for creating a new paper.
     */
    public function create()
    {
        return view('admin.papers.create');
    }

    /**
     * Show the form for editing the specified paper.
     */
    public function edit(Paper $paper)
    {
        return view('admin.papers.edit', compact('paper'));
    }

    /**
     * Remove the specified paper from storage.
     */
    public function destroy(Paper $paper)
    {
        // Detach all questions first
        $paper->questions()->detach();
        
        // Delete the paper
        $paper->delete();

        return redirect()->route('admin.papers.index')->with('success', 'Paper deleted successfully.');
    }
}

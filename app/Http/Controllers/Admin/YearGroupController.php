<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YearGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class YearGroupController extends Controller
{
    public function index(Request $request)
    {
        $query = YearGroup::query();

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('value')) {
            $query->where('value', 'like', '%' . $request->value . '%');
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $yearGroups = $query->latest()->paginate(10);

        return view('admin.year_groups.index', compact('yearGroups'));
    }

    public function create()
    {
        return view('admin.year_groups.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:year_groups,title',
            'value' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        YearGroup::create($validated);

        return redirect()->route('admin.year-groups.index')->with('success', 'Year Group created successfully.');
    }

    public function edit(YearGroup $yearGroup)
    {
        return view('admin.year_groups.edit', compact('yearGroup'));
    }

    public function update(Request $request, YearGroup $yearGroup)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('year_groups')->ignore($yearGroup->id)],
            'value' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $yearGroup->update($validated);

        return redirect()->route('admin.year-groups.index')->with('success', 'Year Group updated successfully.');
    }

    public function destroy(YearGroup $yearGroup)
    {
        $yearGroup->delete();

        return redirect()->route('admin.year-groups.index')->with('success', 'Year Group deleted successfully.');
    }
}

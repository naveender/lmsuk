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
        $query = Paper::with(['subject', 'class', 'user', 'assignments']);

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
        $academicYears = \App\Models\AcademicYear::where('is_active', true)->orderBy('name', 'desc')->get();
        $yearGroups = \App\Models\YearGroup::where('is_active', true)->orderBy('title')->get();
        $students = \App\Models\User::where('role', 'student')->orderBy('name')->get();

        return view('admin.papers.index', compact('papers', 'subjects', 'classes', 'academicYears', 'yearGroups', 'students'));
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

    /**
     * Get assignments for the specified paper.
     */
    public function getAssignments(Paper $paper)
    {
        $assignments = $paper->assignments;
        
        $assignType = 'none';
        $assignMode = 'all';
        $selectedIds = [];

        if ($assignments->isNotEmpty()) {
            $first = $assignments->first();
            $assignType = $first->assign_type;
            $assignMode = $first->assign_mode;
            
            if ($assignType === 'group_years') {
                $selectedIds = $assignments->pluck('target_value')->filter()->toArray();
            } else {
                $selectedIds = $assignments->pluck('target_id')->filter()->toArray();
            }
        }

        return response()->json([
            'assign_type'  => $assignType,
            'assign_mode'  => $assignMode,
            'selected_ids' => $selectedIds,
        ]);
    }

    /**
     * Assign paper to students based on criteria.
     */
    public function assign(Request $request, Paper $paper)
    {
        $validated = $request->validate([
            'assign_scope' => 'required_without:assign_type|nullable|in:none,classes_all,classes_specific,sessions_all,sessions_specific,group_years_all,group_years_specific,students_all,students_specific',
            'assign_type' => 'required_without:assign_scope|nullable|in:none,classes,sessions,group_years,students',
            'assign_mode' => 'required_with:assign_type|nullable|in:all,specific',
            'target_ids' => 'nullable|array',
            'target_values' => 'nullable|array',
        ]);

        if ($request->filled('assign_scope')) {
            $scope = $validated['assign_scope'];
            if ($scope === 'none') {
                $assignType = 'none';
                $assignMode = 'all';
            } else {
                if (str_contains($scope, 'group_years')) {
                    $assignType = 'group_years';
                    $assignMode = str_replace('group_years_', '', $scope);
                } else {
                    $parts = explode('_', $scope);
                    $assignType = $parts[0];
                    $assignMode = $parts[1];
                }
            }
        } else {
            $assignType = $validated['assign_type'] ?? 'none';
            $assignMode = $validated['assign_mode'] ?? 'all';
        }

        if ($assignType !== 'none' && $assignMode === 'specific') {
            if ($assignType === 'group_years' && empty($validated['target_values'])) {
                return $request->expectsJson() || $request->ajax()
                    ? response()->json(['success' => false, 'errors' => ['target_values' => ['Please select at least one group year.']]], 422)
                    : redirect()->back()->withErrors(['target_values' => 'Please select at least one group year.']);
            }
            if ($assignType !== 'group_years' && empty($validated['target_ids'])) {
                return $request->expectsJson() || $request->ajax()
                    ? response()->json(['success' => false, 'errors' => ['target_ids' => ['Please select at least one selection.']]], 422)
                    : redirect()->back()->withErrors(['target_ids' => 'Please select at least one selection.']);
            }
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($paper, $assignType, $assignMode, $validated) {
            // Delete existing assignments first
            $paper->assignments()->delete();

            if ($assignType === 'none') {
                return;
            }

            if ($assignMode === 'all') {
                $paper->assignments()->create([
                    'assign_type' => $assignType,
                    'assign_mode' => 'all',
                ]);
            } else {
                if ($assignType === 'group_years') {
                    $values = $validated['target_values'] ?? [];
                    foreach ($values as $val) {
                        $paper->assignments()->create([
                            'assign_type' => $assignType,
                            'assign_mode' => 'specific',
                            'target_value' => $val,
                        ]);
                    }
                } else {
                    $ids = $validated['target_ids'] ?? [];
                    foreach ($ids as $id) {
                        $paper->assignments()->create([
                            'assign_type' => $assignType,
                            'assign_mode' => 'specific',
                            'target_id' => $id,
                        ]);
                    }
                }
            }
        });

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Paper assignment updated successfully.',
            ]);
        }

        return redirect()->route('admin.papers.index')->with('success', 'Paper assignment updated successfully.');
    }
}

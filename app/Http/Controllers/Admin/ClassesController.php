<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClassesController extends Controller
{
    public function index(Request $request)
    {
        $query = Classes::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('group_year')) {
            $query->where('group_year', $request->group_year);
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $classes = $query->latest()->paginate(10);
        
        $yearGroups = \App\Models\YearGroup::where('is_active', true)->orderBy('title')->get();
        $academicYears = AcademicYear::where('is_active', true)->orderBy('name', 'desc')->get();

        return view('admin.classes.index', compact('classes', 'yearGroups', 'academicYears'));
    }

    public function create()
    {
        $yearGroups = \App\Models\YearGroup::where('is_active', true)->orderBy('title')->get();
        $academicYears = AcademicYear::where('is_active', true)->orderBy('name', 'desc')->get();
        return view('admin.classes.create', compact('yearGroups', 'academicYears'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:classes,name',
            'group_year' => 'required|string|max:255',
            'academic_year' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Classes::create($validated);

        return redirect()->route('admin.classes.index')->with('success', 'Class created successfully.');
    }

    public function edit(Classes $class)
    {
        $yearGroups = \App\Models\YearGroup::where('is_active', true)->orderBy('title')->get();
        $academicYears = AcademicYear::where('is_active', true)->orderBy('name', 'desc')->get();
        return view('admin.classes.edit', compact('class', 'yearGroups', 'academicYears'));
    }

    public function update(Request $request, Classes $class)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('classes')->ignore($class->id)],
            'group_year' => 'required|string|max:255',
            'academic_year' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $class->update($validated);

        return redirect()->route('admin.classes.index')->with('success', 'Class updated successfully.');
    }

    public function destroy(Classes $class)
    {
        $class->delete();

        return redirect()->route('admin.classes.index')->with('success', 'Class deleted successfully.');
    }

    public function students(Classes $class, Request $request)
    {
        $class->load('students.studentDetail');

        $currentStudents = $class->students()->paginate(10, ['*'], 'current_page');

        // Query to find student users who are NOT currently in this class
        $query = \App\Models\User::where('role', 'student')
            ->whereDoesntHave('classes', function ($q) use ($class) {
                $q->where('classes.id', $class->id);
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('group_year')) {
            $query->whereHas('studentDetail', function($q) use ($request) {
                $q->where('group_year', $request->group_year);
            });
        }

        if ($request->filled('academic_year')) {
            $query->whereHas('studentDetail', function($q) use ($request) {
                $q->where('academic_year', $request->academic_year);
            });
        }

        $availableStudents = $query->latest()->paginate(10, ['*'], 'available_page');

        $yearGroups = \App\Models\YearGroup::where('is_active', true)->orderBy('title')->get();
        $academicYears = AcademicYear::where('is_active', true)->orderBy('name', 'desc')->get();

        return view('admin.classes.students', compact('class', 'currentStudents', 'availableStudents', 'yearGroups', 'academicYears'));
    }

    public function addStudents(Request $request, Classes $class)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:users,id',
        ]);

        // Filter to ensure all selected IDs are indeed student users
        $studentIds = \App\Models\User::whereIn('id', $request->student_ids)
            ->where('role', 'student')
            ->pluck('id')
            ->toArray();

        if (empty($studentIds)) {
            return redirect()->back()->with('error', 'No valid students selected.');
        }

        $class->students()->syncWithoutDetaching($studentIds);

        return redirect()->back()->with('success', count($studentIds) . ' student(s) successfully added to the class.');
    }

    public function removeStudent(Classes $class, \App\Models\User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        $class->students()->detach($student->id);

        return redirect()->back()->with('success', 'Student "' . $student->name . '" removed from the class.');
    }
}

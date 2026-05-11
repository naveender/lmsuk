<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StudentDetail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'student')->with('studentDetail');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $students = $query->latest()->paginate(20);
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'date_of_birth' => 'nullable|date',
            'grade' => 'nullable|string|max:255',
            'school' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'student',
            ]);

            $user->studentDetail()->create([
                'date_of_birth' => $request->date_of_birth,
            ]);
        });

        return redirect()->route('admin.students.index')->with('success', 'Student created successfully.');
    }

    public function edit(User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$student->id,
            'date_of_birth' => 'nullable|date',
            'grade' => 'nullable|string|max:255',
            'school' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $student) {
            $student->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $student->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            if ($student->studentDetail) {
                $student->studentDetail->update([
                    'date_of_birth' => $request->date_of_birth,
                ]);
            } else {
                $student->studentDetail()->create([
                    'date_of_birth' => $request->date_of_birth,
                ]);
            }
        });

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }
        if ($student->studentDetail) {
            $student->studentDetail->delete();
        }
        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully.');
    }
}

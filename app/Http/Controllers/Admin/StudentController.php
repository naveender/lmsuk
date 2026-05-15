<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StudentDetail;
use App\Models\ParentDetail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\YearGroup;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'student')->with(['studentDetail.parent']);

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
        $parents = User::where('role', 'parent')->get();
        $yearGroups = YearGroup::where('is_active', 1)->get();
        return view('admin.students.create', compact('parents', 'yearGroups'));
    }

    public function store(Request $request)
    {
        // Common student validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'date_of_birth' => 'required|date',
            'group_year' => 'required|string|max:255',
            'academic_year' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'student_phone' => 'nullable|string|max:255',
            'gender' => 'required|string|in:male,female,other',
            'parent_mode' => 'required|in:existing,new',
        ];

        // Conditional parent validation based on selected mode
        if ($request->parent_mode === 'existing') {
            $rules['parent_id'] = 'required|exists:users,id';
        } else {
            // Inline new parent fields
            $rules['parent_name'] = 'required|string|max:255';
            $rules['parent_username'] = 'required|string|max:255|unique:users,username';
            $rules['parent_email'] = 'required|string|email|max:255|unique:users,email';
            $rules['parent_password'] = 'required|string|min:8';
            $rules['parent_phone'] = 'required|string|max:255';
            $rules['parent_relation'] = 'required|string|max:255';
            $rules['parent_alternate_phone'] = 'nullable|string|max:255';
            $rules['parent_emergency_contact'] = 'nullable|string|max:255';
        }

        $request->validate($rules);

        DB::transaction(function () use ($request) {
            // Determine parent_id: either existing or create new parent inline
            if ($request->parent_mode === 'new') {
                $parentUser = User::create([
                    'name' => $request->parent_name,
                    'username' => $request->parent_username,
                    'email' => $request->parent_email,
                    'password' => Hash::make($request->parent_password),
                    'role' => 'parent',
                ]);

                $parentUser->parentDetail()->create([
                    'phone' => $request->parent_phone,
                    'relation' => $request->parent_relation,
                    'alternate_phone' => $request->parent_alternate_phone,
                    'emergency_contact' => $request->parent_emergency_contact,
                ]);

                $parentId = $parentUser->id;
            } else {
                $parentId = $request->parent_id;
            }

            // Create the student user
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'student',
            ]);

            $user->studentDetail()->create([
                'parent_id' => $parentId,
                'date_of_birth' => $request->date_of_birth,
                'group_year' => $request->group_year,
                'academic_year' => $request->academic_year,
                'region' => $request->region,
                'student_phone' => $request->student_phone,
                'gender' => $request->gender,
            ]);
        });

        return redirect()->route('admin.students.index')->with('success', 'Student created successfully.');
    }

    public function edit(User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }
        $parents = User::where('role', 'parent')->get();
        $yearGroups = YearGroup::where('is_active', 1)->get();
        return view('admin.students.edit', compact('student', 'parents', 'yearGroups'));
    }

    public function update(Request $request, User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.$student->id,
            'email' => 'required|string|email|max:255|unique:users,email,'.$student->id,
            'parent_id' => 'required|exists:users,id',
            'date_of_birth' => 'required|date',
            'group_year' => 'required|string|max:255',
            'academic_year' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'student_phone' => 'nullable|string|max:255',
            'gender' => 'required|string|in:male,female,other',
        ]);

        DB::transaction(function () use ($request, $student) {
            $student->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $student->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            if ($student->studentDetail) {
                $student->studentDetail->update([
                    'parent_id' => $request->parent_id,
                    'date_of_birth' => $request->date_of_birth,
                    'group_year' => $request->group_year,
                    'academic_year' => $request->academic_year,
                    'region' => $request->region,
                    'student_phone' => $request->student_phone,
                    'gender' => $request->gender,
                ]);
            } else {
                $student->studentDetail()->create([
                    'parent_id' => $request->parent_id,
                    'date_of_birth' => $request->date_of_birth,
                    'group_year' => $request->group_year,
                    'academic_year' => $request->academic_year,
                    'region' => $request->region,
                    'student_phone' => $request->student_phone,
                    'gender' => $request->gender,
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

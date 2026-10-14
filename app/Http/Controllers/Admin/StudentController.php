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
use App\Models\AcademicYear;
use App\Models\Classes;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'student')->with(['studentDetail.parent', 'classes']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $cleanId = preg_replace('/[^0-9]/', '', $search);
            $query->where(function($q) use ($search, $cleanId) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
                if (!empty($cleanId)) {
                    $q->orWhere('id', (int)$cleanId);
                }
            });
        }

        if ($request->filled('year_group')) {
            $yearGroup = $request->year_group;
            $ygModel = YearGroup::where('value', $yearGroup)->orWhere('title', $yearGroup)->first();
            $possibleValues = [$yearGroup];
            if ($ygModel) {
                $possibleValues[] = $ygModel->value;
                $possibleValues[] = $ygModel->title;
            }
            $possibleValues = array_unique(array_filter($possibleValues));

            $query->whereHas('studentDetail', function($q) use ($possibleValues) {
                $q->whereIn('group_year', $possibleValues);
            });
        }

        if ($request->filled('class_id')) {
            $classId = $request->class_id;
            $query->whereHas('classes', function($q) use ($classId) {
                $q->where('classes.id', $classId);
            });
        }

        if ($request->filled('parent_status')) {
            if ($request->parent_status === 'linked') {
                $query->whereHas('studentDetail', function($q) {
                    $q->whereNotNull('parent_id');
                });
            } elseif ($request->parent_status === 'unassigned') {
                $query->where(function($q) {
                    $q->whereDoesntHave('studentDetail')
                      ->orWhereHas('studentDetail', function($sq) {
                          $sq->whereNull('parent_id');
                      });
                });
            }
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $totalStudents = User::where('role', 'student')->count();
        $yearGroups = YearGroup::where('is_active', 1)->get();
        $classes = Classes::where('is_active', true)->orderBy('name')->get();

        $students = $query->latest()->paginate(15);
        return view('admin.students.index', compact('students', 'totalStudents', 'yearGroups', 'classes'));
    }

    public function create()
    {
        $parents = User::where('role', 'parent')->get();
        $yearGroups = YearGroup::where('is_active', 1)->get();
        $academicYears = AcademicYear::where('is_active', 1)->orderBy('name', 'desc')->get();
        $classes = Classes::where('is_active', true)->orderBy('name')->get();
        return view('admin.students.create', compact('parents', 'yearGroups', 'academicYears', 'classes'));
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
            'class_id' => 'nullable|exists:classes,id',
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

            // Assign class if selected
            if ($request->filled('class_id')) {
                $user->classes()->sync([$request->class_id]);
            } elseif ($request->filled('class_ids') && is_array($request->class_ids)) {
                $user->classes()->sync(array_filter($request->class_ids));
            }
        });

        return redirect()->route('admin.students.index')->with('success', 'Student created successfully.');
    }

    public function show($student)
    {
        $studentUser = $student instanceof User ? $student : User::where('id', $student)->firstOrFail();

        if ($studentUser->role !== 'student') {
            abort(404);
        }

        $studentUser->load(['studentDetail.parent', 'studentDetail.academicYear', 'classes']);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'student' => $studentUser
            ]);
        }

        return redirect()->route('admin.students.index');
    }

    public function edit(User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }
        $student->load('classes');
        $parents = User::where('role', 'parent')->get();
        $yearGroups = YearGroup::where('is_active', 1)->get();
        $academicYears = AcademicYear::where('is_active', 1)->orderBy('name', 'desc')->get();
        $classes = Classes::where('is_active', true)->orderBy('name')->get();
        return view('admin.students.edit', compact('student', 'parents', 'yearGroups', 'academicYears', 'classes'));
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
            'class_id' => 'nullable|exists:classes,id',
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

            // Sync or detach class
            if ($request->has('class_id')) {
                if ($request->filled('class_id')) {
                    $student->classes()->sync([$request->class_id]);
                } else {
                    $student->classes()->detach();
                }
            } elseif ($request->has('class_ids')) {
                $student->classes()->sync(array_filter((array) $request->class_ids));
            }
        });

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy($student)
    {
        $studentUser = $student instanceof User ? $student : User::where('id', $student)->firstOrFail();

        if ($studentUser->role !== 'student') {
            abort(404);
        }

        DB::transaction(function () use ($studentUser) {
            if ($studentUser->studentDetail) {
                $studentUser->studentDetail->delete();
            }
            $studentUser->delete();
        });

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Student soft-deleted successfully.'
            ]);
        }

        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully.');
    }
}

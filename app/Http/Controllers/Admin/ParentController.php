<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ParentDetail;
use App\Models\StudentDetail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ParentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'parent')->with(['parentDetail', 'children.user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('parentDetail', function($q) use ($search) {
                      $q->where('phone', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $parents = $query->latest()->paginate(20);
        return view('admin.parents.index', compact('parents'));
    }

    public function create()
    {
        return view('admin.parents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:255',
            'relation' => 'required|string|max:255',
            'alternate_phone' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'parent',
            ]);

            $user->parentDetail()->create([
                'phone' => $request->phone,
                'relation' => $request->relation,
                'alternate_phone' => $request->alternate_phone,
                'emergency_contact' => $request->emergency_contact,
                'address' => $request->address,
            ]);
        });

        return redirect()->route('admin.parents.index')->with('success', 'Parent created successfully.');
    }

    /**
     * Display the parent profile with all linked children (family hierarchy view).
     */
    public function show(User $parent)
    {
        if ($parent->role !== 'parent') {
            abort(404);
        }

        $parent->load(['parentDetail', 'children.user']);

        // Get students that are not yet assigned to any parent (for linking)
        $unassignedStudents = User::where('role', 'student')
            ->whereHas('studentDetail', function ($q) {
                $q->whereNull('parent_id');
            })
            ->get();

        return view('admin.parents.show', compact('parent', 'unassignedStudents'));
    }

    public function edit(User $parent)
    {
        if ($parent->role !== 'parent') {
            abort(404);
        }

        $parent->load(['parentDetail', 'children.user']);

        return view('admin.parents.edit', compact('parent'));
    }

    public function update(Request $request, User $parent)
    {
        if ($parent->role !== 'parent') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.$parent->id,
            'email' => 'required|string|email|max:255|unique:users,email,'.$parent->id,
            'phone' => 'required|string|max:255',
            'relation' => 'required|string|max:255',
            'alternate_phone' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $parent) {
            $parent->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $parent->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            if ($parent->parentDetail) {
                $parent->parentDetail->update([
                    'phone' => $request->phone,
                    'relation' => $request->relation,
                    'alternate_phone' => $request->alternate_phone,
                    'emergency_contact' => $request->emergency_contact,
                    'address' => $request->address,
                ]);
            } else {
                $parent->parentDetail()->create([
                    'phone' => $request->phone,
                    'relation' => $request->relation,
                    'alternate_phone' => $request->alternate_phone,
                    'emergency_contact' => $request->emergency_contact,
                    'address' => $request->address,
                ]);
            }
        });

        return redirect()->route('admin.parents.index')->with('success', 'Parent updated successfully.');
    }

    /**
     * Unlink a student from this parent (set parent_id to null).
     */
    public function unlinkStudent(User $parent, User $student)
    {
        if ($parent->role !== 'parent' || $student->role !== 'student') {
            abort(404);
        }

        if ($student->studentDetail && $student->studentDetail->parent_id == $parent->id) {
            $student->studentDetail->update(['parent_id' => null]);
        }

        return redirect()->back()->with('success', 'Student "' . $student->name . '" has been unlinked from this parent.');
    }

    /**
     * Link an existing student to this parent.
     */
    public function linkStudent(Request $request, User $parent)
    {
        if ($parent->role !== 'parent') {
            abort(404);
        }

        $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        $student = User::findOrFail($request->student_id);

        if ($student->role !== 'student') {
            return redirect()->back()->with('error', 'Selected user is not a student.');
        }

        if ($student->studentDetail) {
            $student->studentDetail->update(['parent_id' => $parent->id]);
        }

        return redirect()->back()->with('success', 'Student "' . $student->name . '" has been linked to this parent.');
    }

    public function destroy(User $parent)
    {
        if ($parent->role !== 'parent') {
            abort(404);
        }

        // Unlink all children before deleting
        StudentDetail::where('parent_id', $parent->id)->update(['parent_id' => null]);

        if ($parent->parentDetail) {
            $parent->parentDetail->delete();
        }
        $parent->delete();

        return redirect()->route('admin.parents.index')->with('success', 'Parent deleted successfully.');
    }
}

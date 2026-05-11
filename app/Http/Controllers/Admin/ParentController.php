<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ParentDetail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ParentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'parent')->with('parentDetail');

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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'parent',
            ]);

            $user->parentDetail()->create([
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        });

        return redirect()->route('admin.parents.index')->with('success', 'Parent created successfully.');
    }

    public function edit(User $parent)
    {
        if ($parent->role !== 'parent') {
            abort(404);
        }
        return view('admin.parents.edit', compact('parent'));
    }

    public function update(Request $request, User $parent)
    {
        if ($parent->role !== 'parent') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$parent->id,
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $parent) {
            $parent->update([
                'name' => $request->name,
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
                    'address' => $request->address,
                ]);
            } else {
                $parent->parentDetail()->create([
                    'phone' => $request->phone,
                    'address' => $request->address,
                ]);
            }
        });

        return redirect()->route('admin.parents.index')->with('success', 'Parent updated successfully.');
    }

    public function destroy(User $parent)
    {
        if ($parent->role !== 'parent') {
            abort(404);
        }
        if ($parent->parentDetail) {
            $parent->parentDetail->delete();
        }
        $parent->delete();

        return redirect()->route('admin.parents.index')->with('success', 'Parent deleted successfully.');
    }
}

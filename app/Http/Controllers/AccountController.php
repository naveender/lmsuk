<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    /**
     * Display the user's account and profile management page.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->isStudent()) {
            $user->load('studentDetail');
        } elseif ($user->isParent()) {
            $user->load('parentDetail');
        }

        return view('account.index', compact('user'));
    }

    /**
     * Update the user's basic profile details.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            // Student specific fields
            'student_phone' => ['nullable', 'string', 'max:25'],
            'gender' => ['nullable', 'string', 'max:25'],
            'date_of_birth' => ['nullable', 'date'],
            // Parent specific fields
            'phone' => ['nullable', 'string', 'max:25'],
            'alternate_phone' => ['nullable', 'string', 'max:25'],
            'address' => ['nullable', 'string', 'max:500'],
            'emergency_contact' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
        ]);

        if ($user->isStudent()) {
            $studentDetail = $user->studentDetail;
            if ($studentDetail) {
                $studentDetail->update([
                    'student_phone' => $request->input('student_phone'),
                    'gender' => $request->input('gender'),
                    'date_of_birth' => $request->input('date_of_birth'),
                ]);
            }
        } elseif ($user->isParent()) {
            $parentDetail = $user->parentDetail;
            if ($parentDetail) {
                $parentDetail->update([
                    'phone' => $request->input('phone'),
                    'alternate_phone' => $request->input('alternate_phone'),
                    'address' => $request->input('address'),
                    'emergency_contact' => $request->input('emergency_contact'),
                ]);
            }
        }

        return redirect()->route('account.index')->with('success', 'Profile information updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.current_password' => 'The provided current password does not match our records.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min' => 'The new password must be at least 8 characters.',
        ]);

        $user = auth()->user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('account.index')->with('success', 'Password changed successfully.');
    }
}

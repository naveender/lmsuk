<?php

namespace App\Http\Controllers;

use App\Services\SettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display settings page.
     */
    public function index(SettingsService $service)
    {
        $settings = $service->getAll();
        $user = auth()->user();

        return view('settings.index', compact('settings', 'user'));
    }

    /**
     * Update application or user preferences.
     */
    public function update(Request $request, SettingsService $service)
    {
        // If theme is passed, persist to session
        if ($request->filled('theme')) {
            $theme = $request->input('theme');
            if (in_array($theme, ['light', 'dark'])) {
                session(['theme' => $theme]);
            }
        }

        // Exclude CSRF token and method from settings payload
        $data = $request->except(['_token', '_method']);
        if (!empty($data)) {
            $service->update($data);
        }

        return back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Backward-compatible profile redirect.
     */
    public function profile()
    {
        return redirect()->route('account.index');
    }
}

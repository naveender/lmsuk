<?php

namespace App\Http\Controllers;

use App\Services\WasabiService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(WasabiService $wasabi)
    {
        $stats = $wasabi->getBackupStats();
        //$stats['DeletedStorageUsed'] = $this->getDeletedStorageUsed();
        return view('dashboard',compact('stats'));
    }

     public function toggleTheme(Request $request)
    {
        $currentTheme = session('theme', $request->cookie('theme', 'light'));
        if (!in_array($currentTheme, ['light', 'dark'])) {
            $currentTheme = 'light';
        }

        $requestedTheme = $request->input('theme');
        if ($requestedTheme && in_array($requestedTheme, ['light', 'dark'])) {
            $newTheme = $requestedTheme;
        } else {
            $newTheme = $currentTheme === 'dark' ? 'light' : 'dark';
        }

        session(['theme' => $newTheme]);
        $cookie = cookie('theme', $newTheme, 525600, '/', null, false, false);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'theme' => $newTheme,
                'layoutClass' => $newTheme . '-layout'
            ])->withCookie($cookie);
        }

        return redirect()->back()->withCookie($cookie);
    }
}

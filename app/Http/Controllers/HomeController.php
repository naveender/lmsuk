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
        $currentTheme = session('theme', 'light'); // default light
        $newTheme = $currentTheme === 'dark' ? 'light' : 'dark';

        session(['theme' => $newTheme]);

         return redirect()->back();
    }
}

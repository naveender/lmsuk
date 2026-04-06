<?php

namespace App\Http\Controllers;

use App\Services\SettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
      public function index(SettingsService $service)
    {
        $settings = $service->getAll();

        return view('settings', compact('settings'));
    }
    
     public function update(Request $request, SettingsService $service)
    {   
        //dd($request->all());
        $service->update($request->except('_token'));

        return back()->with('success', 'Settings updated successfully.');
    }

     public function profile()
    {
        return view('edit-profile');
    }
}

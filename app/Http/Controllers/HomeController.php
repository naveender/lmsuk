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
}

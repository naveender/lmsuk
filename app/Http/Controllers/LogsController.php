<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RestoreLog;

class LogsController extends Controller
{
    public function index()
    {
        $logs = RestoreLog::orderBy('created_at', 'desc')->get();
        return view('logs', compact('logs'));
    }
}

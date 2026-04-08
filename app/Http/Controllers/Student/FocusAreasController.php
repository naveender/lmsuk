<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FocusAreasController extends Controller
{
    public function index()
    {
        return view('student.focusareas.index');
    }
}

<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LessonsController extends Controller
{
    public function index()
    {
        return view('student.lessons.video-lessons-category');
    }

     public function lessionlist()
    {
        return view('student.lessons.lessons-videos-list');
    }
}

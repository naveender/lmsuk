<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function index()
    {
        return view('student.assessment.category-test-overview');
    }
}

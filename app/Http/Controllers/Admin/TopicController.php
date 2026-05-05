<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function index()
    {
        $topics = Topic::orderBy('id', 'desc')->get();
        return view('admin.topics.index', compact('topics'));
    }
    public function add()
    {
       $topics = Topic::where('parent', 0)->get();
        return view('admin.topics.add', compact('topics'));
    }
}

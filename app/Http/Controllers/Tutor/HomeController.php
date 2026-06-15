<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $now = now();

        $unreadHighPriority = Announcement::where('is_draft', false)
            ->where('status', 'active')
            ->where('priority', 'high')
            ->where(function ($q) use ($now) {
                $q->whereNull('show_from')->orWhere('show_from', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', $now);
            })
            ->where(function ($q) use ($user) {
                $q->whereHas('targets', function ($t) use ($user) {
                    $t->where('target_type', 'user')->where('target_id', $user->id);
                });

                $q->orWhereHas('targets', function ($t) {
                    $t->where('target_type', 'all_tutors');
                });
            })
            ->whereDoesntHave('views', function ($v) use ($user) {
                $v->where('user_id', $user->id);
            })
            ->get();

        return view('tutor.dashboard', compact('unreadHighPriority'));
    }
}

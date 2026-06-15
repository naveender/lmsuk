<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\YearGroup;
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
                    $t->where('target_type', 'all_active_students');
                });

                $classIds = $user->classes()->pluck('classes.id')->toArray();
                if (!empty($classIds)) {
                    $q->orWhereHas('targets', function ($t) use ($classIds) {
                        $t->where('target_type', 'class')->whereIn('target_id', $classIds);
                    });
                }

                $studentDetail = $user->studentDetail;
                if ($studentDetail && $studentDetail->group_year) {
                    $yearGroupId = YearGroup::where('value', $studentDetail->group_year)->value('id');
                    if ($yearGroupId) {
                        $q->orWhereHas('targets', function ($t) use ($yearGroupId) {
                            $t->where('target_type', 'year_group')->where('target_id', $yearGroupId);
                        });
                    }
                }
            })
            ->whereDoesntHave('views', function ($v) use ($user) {
                $v->where('user_id', $user->id);
            })
            ->get();

        return view('student.dashboard', compact('unreadHighPriority'));
    }
}

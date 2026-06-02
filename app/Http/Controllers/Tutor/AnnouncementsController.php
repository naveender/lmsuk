<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementView;
use Illuminate\Http\Request;

class AnnouncementsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $now = now();

        $query = Announcement::where('is_draft', false)
            ->where('status', 'active')
            ->where(function ($q) use ($now) {
                $q->whereNull('show_from')
                    ->orWhere('show_from', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', $now);
            });

        $query->where(function ($q) use ($user) {
            // Target individual user
            $q->whereHas('targets', function ($t) use ($user) {
                $t->where('target_type', 'user')
                    ->where('target_id', $user->id);
            });

            // Target all tutors
            $q->orWhereHas('targets', function ($t) {
                $t->where('target_type', 'all_tutors');
            });
        });

        $announcements = $query->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 WHEN 'low' THEN 3 END ASC")
            ->orderBy('show_from', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $viewedIds = AnnouncementView::where('user_id', $user->id)->pluck('announcement_id')->toArray();

        return view('tutor.announcements', compact('announcements', 'viewedIds'));
    }

    public function view(Announcement $announcement)
    {
        $user = auth()->user();

        // Check if eligible
        $isEligible = $announcement->getEligibleRecipientsQuery()->where('users.id', $user->id)->exists();
        if (!$isEligible) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        AnnouncementView::firstOrCreate([
            'announcement_id' => $announcement->id,
            'user_id' => $user->id,
        ], [
            'viewed_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}

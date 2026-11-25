<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementView;
use App\Models\YearGroup;
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

            // Target all active students
            $q->orWhereHas('targets', function ($t) {
                $t->where('target_type', 'all_active_students');
            });

            // Target classes
            $classIds = $user->classes()->pluck('classes.id')->toArray();
            if (!empty($classIds)) {
                $q->orWhereHas('targets', function ($t) use ($classIds) {
                    $t->where('target_type', 'class')
                        ->whereIn('target_id', $classIds);
                });
            }

            // Target year groups
            $studentDetail = $user->studentDetail;
            if ($studentDetail && $studentDetail->group_year) {
                $yearGroupId = YearGroup::where('value', $studentDetail->group_year)->value('id');
                if ($yearGroupId) {
                    $q->orWhereHas('targets', function ($t) use ($yearGroupId) {
                        $t->where('target_type', 'year_group')
                            ->where('target_id', $yearGroupId);
                    });
                }
            }
        });

        $announcements = $query->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 WHEN 'low' THEN 3 END ASC")
            ->orderBy('show_from', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($announcement) {
                [$categoryKey, $categoryLabel] = $this->classifyAnnouncement($announcement);
                $announcement->category_key = $categoryKey;
                $announcement->category_label = $categoryLabel;
                return $announcement;
            });

        $viewedIds = AnnouncementView::where('user_id', $user->id)->pluck('announcement_id')->toArray();

        $totalAnnouncementsCount = $announcements->count();
        $importantCount = $announcements->where('category_key', 'important')->count();
        $upcomingEventsCount = $announcements->where('category_key', 'upcoming')->count();
        $generalUpdatesCount = max(0, $totalAnnouncementsCount - $importantCount - $upcomingEventsCount);

        return view('student.announcements', compact(
            'announcements',
            'viewedIds',
            'totalAnnouncementsCount',
            'importantCount',
            'upcomingEventsCount',
            'generalUpdatesCount'
        ));
    }

    /**
     * Classify an announcement into UI filter categories matching the student portal design.
     */
    private function classifyAnnouncement(Announcement $announcement): array
    {
        $haystack = strtolower(trim(
            ($announcement->title ?? '') . ' ' .
            ($announcement->description ?? '') . ' ' .
            ($announcement->content ?? '')
        ));

        if ($announcement->priority === 'high') {
            return ['important', 'Important'];
        }

        if (
            preg_match('/\b(schedule|timetable|event|events|upcoming|starting|starts|workshop|webinar|calendar|batch|session|sessions|deadline)\b/i', $haystack)
            || ($announcement->show_from && $announcement->show_from->isFuture())
        ) {
            return ['upcoming', 'Upcoming Event'];
        }

        if (preg_match('/\b(holiday|holidays|closed|closure|bank holiday)\b/i', $haystack)) {
            return ['general', 'General'];
        }

        if (preg_match('/\b(result|results|published|portal|report|feedback|score|scores|centre update|system update|maintenance)\b/i', $haystack)) {
            return ['centre_update', 'Centre Update'];
        }

        if (preg_match('/\b(paper|papers|homework|worksheet|worksheets|lesson|lessons|practice|english|math|maths|verbal|revision|study|resource|resources|topic|curriculum|quiz|mock)\b/i', $haystack)) {
            return ['academic', 'Academic'];
        }

        return ['general', 'General'];
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

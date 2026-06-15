<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AcademicYear;
use App\Models\Classes;
use App\Models\YearGroup;
use App\Models\User;
use App\Models\AnnouncementAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::query()->with(['academicYear', 'targets', 'views']);

        // Search by keyword
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by Type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by Academic Year
        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        // Filter by Recipient Type
        if ($request->filled('recipient_type')) {
            $query->whereHas('targets', function ($t) use ($request) {
                $t->where('target_type', $request->recipient_type);
            });
        }

        // Filter by Date Range
        if ($request->filled('start_date')) {
            $query->whereDate('show_from', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('show_from', '<=', $request->end_date);
        }

        // Filter by computed status
        if ($request->filled('status')) {
            $status = $request->status;
            $now = now();
            if ($status === 'active') {
                $query->where('status', 'active')
                    ->where('is_draft', false)
                    ->where(function ($q) use ($now) {
                        $q->whereNull('show_from')->orWhere('show_from', '<=', $now);
                    })
                    ->where(function ($q) use ($now) {
                        $q->whereNull('expires_at')->orWhere('expires_at', '>', $now);
                    });
            } elseif ($status === 'draft') {
                $query->where('is_draft', true)->where('status', '!=', 'deleted');
            } elseif ($status === 'scheduled') {
                $query->where('is_draft', false)
                    ->where('status', 'active')
                    ->whereNotNull('show_from')
                    ->where('show_from', '>', $now);
            } elseif ($status === 'expired') {
                $query->where(function ($q) use ($now) {
                    $q->where('status', 'expired')
                        ->orWhere(function ($sq) use ($now) {
                            $sq->whereNotNull('expires_at')->where('expires_at', '<=', $now);
                        });
                });
            } elseif ($status === 'deleted') {
                $query->where('status', 'deleted');
            }
        } else {
            // Hide deleted announcements by default
            $query->where('status', '!=', 'deleted');
        }

        $announcements = $query->latest()->paginate(10)->appends($request->all());

        // Attach dynamic stats to each announcement for view
        foreach ($announcements as $announcement) {
            $announcement->recipients_count = $announcement->getEligibleRecipientsQuery()->count();
            $announcement->views_count = $announcement->views()->count();
        }

        $academicYears = AcademicYear::where('is_active', true)->orderBy('name', 'desc')->get();

        return view('admin.announcements.index', compact('announcements', 'academicYears'));
    }

    public function create()
    {
        $academicYears = AcademicYear::where('is_active', true)->orderBy('name', 'desc')->get();
        $classes = Classes::where('is_active', true)->orderBy('name')->get();
        $yearGroups = YearGroup::where('is_active', true)->orderBy('title')->get();
        $users = User::orderBy('name')->get();

        return view('admin.announcements.create', compact('academicYears', 'classes', 'yearGroups', 'users'));
    }

    public function store(Request $request)
    {
        $isDraft = $request->has('is_draft');

        $rules = [
            'type' => 'required|integer|in:1,2,3',
            'title' => 'required|string|max:255',
            'content' => 'required_if:type,1,3|nullable|string',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:10240',
            'description' => 'required_if:type,3|nullable|string',
            'academic_year_id' => 'required|exists:academic_years,id',
            'priority' => 'required|in:high,medium,low',
            'show_from' => 'nullable|date',
            'expires_at' => 'nullable|date|after:show_from',
        ];

        $request->validate($rules);

        $hasRecipients = $request->has('target_all_active_students') ||
            $request->has('target_all_tutors') ||
            ($request->filled('target_classes') && count($request->target_classes) > 0) ||
            ($request->filled('target_users') && count($request->target_users) > 0) ||
            ($request->filled('target_year_groups') && count($request->target_year_groups) > 0);

        if (!$isDraft && !$hasRecipients) {
            return redirect()->back()->withErrors(['recipients' => 'At least one recipient group or individual user must be selected before publishing.'])->withInput();
        }

        $data = $request->only(['type', 'title', 'content', 'description', 'academic_year_id', 'priority', 'show_from', 'expires_at']);
        $data['is_draft'] = $isDraft;
        $data['status'] = 'active';

        if ($request->hasFile('media')) {
            $data['media'] = $request->file('media')->store('announcements', 'public');
        }

        $announcement = DB::transaction(function () use ($data, $request) {
            $announcement = Announcement::create($data);

            if ($request->has('target_all_active_students')) {
                $announcement->targets()->create(['target_type' => 'all_active_students']);
            }
            if ($request->has('target_all_tutors')) {
                $announcement->targets()->create(['target_type' => 'all_tutors']);
            }
            if ($request->filled('target_classes')) {
                foreach ($request->target_classes as $classId) {
                    $announcement->targets()->create(['target_type' => 'class', 'target_id' => $classId]);
                }
            }
            if ($request->filled('target_year_groups')) {
                foreach ($request->target_year_groups as $yearGroupId) {
                    $announcement->targets()->create(['target_type' => 'year_group', 'target_id' => $yearGroupId]);
                }
            }
            if ($request->filled('target_users')) {
                foreach ($request->target_users as $userId) {
                    $announcement->targets()->create(['target_type' => 'user', 'target_id' => $userId]);
                }
            }

            return $announcement;
        });

        AnnouncementAuditLog::log($announcement->id, 'created', 'Announcement created' . ($isDraft ? ' as draft' : ' and published') . '.');

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement created successfully.');
    }

    public function edit(Announcement $announcement)
    {
        $academicYears = AcademicYear::where('is_active', true)->orderBy('name', 'desc')->get();
        $classes = Classes::where('is_active', true)->orderBy('name')->get();
        $yearGroups = YearGroup::where('is_active', true)->orderBy('title')->get();
        $users = User::orderBy('name')->get();

        $currentTargetTypes = $announcement->targets->pluck('target_type')->toArray();
        $currentClasses = $announcement->targets->where('target_type', 'class')->pluck('target_id')->toArray();
        $currentYearGroups = $announcement->targets->where('target_type', 'year_group')->pluck('target_id')->toArray();
        $currentUsers = $announcement->targets->where('target_type', 'user')->pluck('target_id')->toArray();

        return view('admin.announcements.edit', compact(
            'announcement',
            'academicYears',
            'classes',
            'yearGroups',
            'users',
            'currentTargetTypes',
            'currentClasses',
            'currentYearGroups',
            'currentUsers'
        ));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $isDraft = $request->has('is_draft');

        $rules = [
            'type' => 'required|integer|in:1,2,3',
            'title' => 'required|string|max:255',
            'content' => 'required_if:type,1,3|nullable|string',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:10240',
            'description' => 'required_if:type,3|nullable|string',
            'academic_year_id' => 'required|exists:academic_years,id',
            'priority' => 'required|in:high,medium,low',
            'show_from' => 'nullable|date',
            'expires_at' => 'nullable|date|after:show_from',
        ];

        $request->validate($rules);

        $hasRecipients = $request->has('target_all_active_students') ||
            $request->has('target_all_tutors') ||
            ($request->filled('target_classes') && count($request->target_classes) > 0) ||
            ($request->filled('target_users') && count($request->target_users) > 0) ||
            ($request->filled('target_year_groups') && count($request->target_year_groups) > 0);

        if (!$isDraft && !$hasRecipients) {
            return redirect()->back()->withErrors(['recipients' => 'At least one recipient group or individual user must be selected before publishing.'])->withInput();
        }

        $data = $request->only(['type', 'title', 'content', 'description', 'academic_year_id', 'priority', 'show_from', 'expires_at']);
        $data['is_draft'] = $isDraft;

        if ($request->hasFile('media')) {
            if ($announcement->media && Storage::disk('public')->exists($announcement->media)) {
                Storage::disk('public')->delete($announcement->media);
            }
            $data['media'] = $request->file('media')->store('announcements', 'public');
        }

        $wasDraft = $announcement->is_draft;

        DB::transaction(function () use ($announcement, $data, $request) {
            $announcement->update($data);

            $announcement->targets()->delete();

            if ($request->has('target_all_active_students')) {
                $announcement->targets()->create(['target_type' => 'all_active_students']);
            }
            if ($request->has('target_all_tutors')) {
                $announcement->targets()->create(['target_type' => 'all_tutors']);
            }
            if ($request->filled('target_classes')) {
                foreach ($request->target_classes as $classId) {
                    $announcement->targets()->create(['target_type' => 'class', 'target_id' => $classId]);
                }
            }
            if ($request->filled('target_year_groups')) {
                foreach ($request->target_year_groups as $yearGroupId) {
                    $announcement->targets()->create(['target_type' => 'year_group', 'target_id' => $yearGroupId]);
                }
            }
            if ($request->filled('target_users')) {
                foreach ($request->target_users as $userId) {
                    $announcement->targets()->create(['target_type' => 'user', 'target_id' => $userId]);
                }
            }
        });

        $actionLog = 'Announcement updated.';
        $action = 'updated';
        if ($wasDraft && !$isDraft) {
            $actionLog = 'Announcement published.';
            $action = 'published';
        }
        AnnouncementAuditLog::log($announcement->id, $action, $actionLog);

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->media && Storage::disk('public')->exists($announcement->media)) {
            Storage::disk('public')->delete($announcement->media);
            $announcement->update(['media' => null]);
        }
        $announcement->update(['status' => 'deleted']);
        AnnouncementAuditLog::log($announcement->id, 'deleted', 'Announcement deleted.');

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement deleted successfully.');
    }

    public function stats(Announcement $announcement)
    {
        $recipients = $announcement->getEligibleRecipientsQuery()->get(['id', 'name', 'email', 'role']);
        $views = $announcement->views->keyBy('user_id');

        $stats = $recipients->map(function ($user) use ($views) {
            $view = $views->get($user->id);
            return [
                'name' => $user->name,
                'email' => $user->email,
                'role' => ucfirst($user->role),
                'viewed' => $view ? true : false,
                'viewed_at' => $view ? $view->viewed_at->format('Y-m-d H:i:s') : '-',
            ];
        });

        return response()->json($stats);
    }

    public function auditLogs(Announcement $announcement)
    {
        $logs = $announcement->auditLogs()->with('user')->orderBy('created_at', 'desc')->get()->map(function ($log) {
            return [
                'admin' => $log->user ? $log->user->name : 'System',
                'action' => ucfirst($log->action),
                'details' => $log->details,
                'created_at' => $log->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json($logs);
    }
}

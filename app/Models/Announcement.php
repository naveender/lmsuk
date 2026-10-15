<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'type',
        'title',
        'content',
        'media',
        'description',
        'status',
        'academic_year_id',
        'is_draft',
        'show_from',
        'expires_at',
        'priority',
    ];

    protected $casts = [
        'is_draft' => 'boolean',
        'show_from' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function targets()
    {
        return $this->hasMany(AnnouncementTarget::class);
    }

    public function views()
    {
        return $this->hasMany(AnnouncementView::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AnnouncementAuditLog::class);
    }

    public function getComputedStatusAttribute()
    {
        if ($this->status === 'deleted') {
            return 'Deleted';
        }
        if ($this->is_draft) {
            return 'Draft';
        }
        if ($this->show_from && $this->show_from->isFuture()) {
            return 'Scheduled';
        }
        if ($this->expires_at && $this->expires_at->isPast()) {
            return 'Expired';
        }
        return 'Active';
    }

    public function getRecipientScopeSummariesAttribute()
    {
        $summaries = [];
        $targets = $this->targets;

        if ($targets->contains('target_type', 'all_active_students')) {
            $summaries[] = [
                'type' => 'all_active_students',
                'label' => 'All Students',
                'icon' => 'users',
                'badge_bg' => 'rgba(36, 119, 249, 0.1)',
                'badge_color' => 'var(--theme-primary-bright, #2477F9)',
                'tooltip' => 'All students active within last 12 months',
            ];
        }

        if ($targets->contains('target_type', 'all_tutors')) {
            $summaries[] = [
                'type' => 'all_tutors',
                'label' => 'All Tutors',
                'icon' => 'user-check',
                'badge_bg' => 'rgba(115, 103, 240, 0.12)',
                'badge_color' => '#2477F9',
                'tooltip' => 'All registered tutors',
            ];
        }

        $classTargets = $targets->where('target_type', 'class');
        if ($classTargets->isNotEmpty()) {
            $classNames = $classTargets->map(function ($t) {
                return $t->classModel ? $t->classModel->name : ('Class #' . $t->target_id);
            })->filter()->values();

            $count = $classTargets->count();
            $label = $count === 1 ? ($classNames->first() ?? '1 Class') : ($count . ' Classes');

            $summaries[] = [
                'type' => 'class',
                'label' => $label,
                'icon' => 'book-open',
                'badge_bg' => 'rgba(40, 199, 111, 0.12)',
                'badge_color' => '#28C76F',
                'tooltip' => $classNames->implode(', '),
            ];
        }

        $yearGroupTargets = $targets->where('target_type', 'year_group');
        if ($yearGroupTargets->isNotEmpty()) {
            $ygNames = $yearGroupTargets->map(function ($t) {
                return $t->yearGroup ? $t->yearGroup->title : ('Year Group #' . $t->target_id);
            })->filter()->values();

            $count = $yearGroupTargets->count();
            $label = $count === 1 ? ($ygNames->first() ?? '1 Year Group') : ($count . ' Year Groups');

            $summaries[] = [
                'type' => 'year_group',
                'label' => $label,
                'icon' => 'layers',
                'badge_bg' => 'rgba(255, 159, 67, 0.15)',
                'badge_color' => '#B26A00',
                'tooltip' => $ygNames->implode(', '),
            ];
        }

        $userTargets = $targets->where('target_type', 'user');
        if ($userTargets->isNotEmpty()) {
            $userNames = $userTargets->map(function ($t) {
                return $t->user ? $t->user->name : ('User #' . $t->target_id);
            })->filter()->values();

            $count = $userTargets->count();
            $label = $count === 1 ? ($userNames->first() ?? '1 User') : ($count . ' Users');

            $summaries[] = [
                'type' => 'user',
                'label' => $label,
                'icon' => 'user',
                'badge_bg' => 'rgba(0, 181, 216, 0.12)',
                'badge_color' => '#0096B4',
                'tooltip' => $userNames->implode(', '),
            ];
        }

        return $summaries;
    }

    public function getRecipientScopeTextAttribute()
    {
        $summaries = $this->recipient_scope_summaries;
        if (empty($summaries)) {
            return 'No Recipients';
        }
        return collect($summaries)->pluck('label')->implode(', ');
    }

    public function getEligibleRecipientsQuery()
    {
        $targets = $this->targets;

        $hasAllActiveStudents = false;
        $hasAllTutors = false;
        $classIds = [];
        $yearGroupIds = [];
        $individualUserIds = [];

        foreach ($targets as $target) {
            if ($target->target_type === 'all_active_students') {
                $hasAllActiveStudents = true;
            } elseif ($target->target_type === 'all_tutors') {
                $hasAllTutors = true;
            } elseif ($target->target_type === 'class') {
                $classIds[] = $target->target_id;
            } elseif ($target->target_type === 'year_group') {
                $yearGroupIds[] = $target->target_id;
            } elseif ($target->target_type === 'user') {
                $individualUserIds[] = $target->target_id;
            }
        }

        $query = User::query();

        $query->where(function ($q) use ($hasAllActiveStudents, $hasAllTutors, $classIds, $yearGroupIds, $individualUserIds) {
            $q->whereIn('id', $individualUserIds);

            if ($hasAllTutors) {
                $q->orWhere('role', 'tutor');
            }

            if ($hasAllActiveStudents) {
                $q->orWhere(function ($sq) {
                    $sq->where('role', 'student')
                        ->where(function ($sq2) {
                            $sq2->where('created_at', '>=', now()->subMonths(12))
                                ->orWhereHas('studentDetail', function ($sd) {
                                    $sd->where('updated_at', '>=', now()->subMonths(12));
                                })
                                ->orWhereHas('paperAttempts', function ($pa) {
                                    $pa->where('created_at', '>=', now()->subMonths(12));
                                })
                                ->orWhereHas('classes', function ($c) {
                                    $c->where('class_student.created_at', '>=', now()->subMonths(12));
                                });
                        });
                });
            }

            if (!empty($classIds)) {
                $q->orWhereHas('classes', function ($cq) use ($classIds) {
                    $cq->whereIn('classes.id', $classIds);
                });
            }

            if (!empty($yearGroupIds)) {
                $yearGroupValues = YearGroup::whereIn('id', $yearGroupIds)->pluck('value')->toArray();
                if (!empty($yearGroupValues)) {
                    $q->orWhereHas('studentDetail', function ($sq) use ($yearGroupValues) {
                        $sq->whereIn('group_year', $yearGroupValues);
                    });
                }
            }
        });

        return $query;
    }
}

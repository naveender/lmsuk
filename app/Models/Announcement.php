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

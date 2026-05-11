<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name', 'project_title', 'semester_id', 'supervisor_id', 'created_by', 'status'];

    const STATUS_FORMING      = 'forming';
    const STATUS_ACTIVE       = 'active';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_APPROVED     = 'approved';
    const STATUS_COMPLETED    = 'completed';
    const STATUS_ARCHIVED     = 'archived';

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members()
    {
        return $this->hasMany(TeamMember::class)->where('status', 'active');
    }

    public function allMembers()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function leader()
    {
        return $this->hasOne(TeamMember::class)->where('role', 'leader')->where('status', 'active');
    }

    public function invitations()
    {
        return $this->hasMany(TeamInvitation::class);
    }

    public function proposal()
    {
        return $this->hasOne(Proposal::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class)->where('status', 'active')->latest();
    }

    public function teamMilestones()
    {
        return $this->hasMany(TeamMilestone::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->oldest();
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class)->latest('scheduled_at');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class)->latest('evaluation_date');
    }

    public function presentations()
    {
        return $this->hasMany(Presentation::class)->latest('scheduled_at');
    }

    public function hasStudent(int $userId): bool
    {
        return $this->members()->where('student_id', $userId)->exists();
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'forming'      => '<span class="badge bg-secondary">Forming</span>',
            'active'       => '<span class="badge bg-primary">Active</span>',
            'under_review' => '<span class="badge bg-warning text-dark">Under Review</span>',
            'approved'     => '<span class="badge bg-success">Approved</span>',
            'completed'    => '<span class="badge bg-info">Completed</span>',
            'archived'     => '<span class="badge bg-dark">Archived</span>',
            default        => '<span class="badge bg-secondary">' . ucfirst($this->status) . '</span>',
        };
    }
}

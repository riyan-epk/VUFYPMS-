<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    protected $fillable = [
        'team_id', 'title', 'abstract', 'domain_id', 'tools_technologies',
        'objectives', 'status', 'revision_notes', 'submitted_at', 'reviewed_at', 'reviewed_by',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at'  => 'datetime',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function domain()
    {
        return $this->belongsTo(ProjectDomain::class, 'domain_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'draft'             => '<span class="badge bg-secondary">Draft</span>',
            'submitted'         => '<span class="badge bg-primary">Submitted</span>',
            'under_review'      => '<span class="badge bg-warning text-dark">Under Review</span>',
            'revision_required' => '<span class="badge bg-danger">Revision Required</span>',
            'approved'          => '<span class="badge bg-success">Approved</span>',
            'rejected'          => '<span class="badge bg-dark">Rejected</span>',
            default             => '<span class="badge bg-secondary">' . ucfirst($this->status) . '</span>',
        };
    }

    public function isEditable(): bool
    {
        return in_array($this->status, ['draft', 'revision_required']);
    }

    public function canSubmit(): bool
    {
        return in_array($this->status, ['draft', 'revision_required']);
    }
}

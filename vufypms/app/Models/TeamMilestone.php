<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMilestone extends Model
{
    protected $fillable = ['team_id', 'milestone_id', 'status', 'completion_notes', 'completed_at'];

    protected $casts = ['completed_at' => 'datetime'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function milestone()
    {
        return $this->belongsTo(Milestone::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending'     => '<span class="badge bg-secondary">Pending</span>',
            'in_progress' => '<span class="badge bg-warning text-dark">In Progress</span>',
            'completed'   => '<span class="badge bg-success">Completed</span>',
            'overdue'     => '<span class="badge bg-danger">Overdue</span>',
            default       => '<span class="badge bg-secondary">' . ucfirst($this->status) . '</span>',
        };
    }
}

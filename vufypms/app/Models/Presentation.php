<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    protected $fillable = [
        'team_id', 'type', 'scheduled_at', 'venue',
        'online_link', 'panel_info', 'duration_minutes', 'status',
    ];

    protected $casts = ['scheduled_at' => 'datetime'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'proposal_defense' => 'Proposal Defense',
            'progress_review'  => 'Progress Review',
            'final_defense'    => 'Final Defense',
            default            => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'scheduled'  => '<span class="badge bg-primary">Scheduled</span>',
            'completed'  => '<span class="badge bg-success">Completed</span>',
            'postponed'  => '<span class="badge bg-warning text-dark">Postponed</span>',
            'cancelled'  => '<span class="badge bg-danger">Cancelled</span>',
            default      => '<span class="badge bg-secondary">' . ucfirst($this->status) . '</span>',
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'team_id', 'supervisor_id', 'title', 'scheduled_at',
        'venue', 'meeting_link', 'notes', 'status',
    ];

    protected $casts = ['scheduled_at' => 'datetime'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'scheduled'  => '<span class="badge bg-primary">Scheduled</span>',
            'completed'  => '<span class="badge bg-success">Completed</span>',
            'cancelled'  => '<span class="badge bg-danger">Cancelled</span>',
            default      => '<span class="badge bg-secondary">' . ucfirst($this->status) . '</span>',
        };
    }
}

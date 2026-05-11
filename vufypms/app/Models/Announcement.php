<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'created_by', 'title', 'content', 'type',
        'is_public', 'target_role', 'published_at', 'expires_at',
    ];

    protected $casts = [
        'is_public'    => 'boolean',
        'published_at' => 'datetime',
        'expires_at'   => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
                     ->where('published_at', '<=', now())
                     ->where(function ($q) {
                         $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                     });
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function getTypeBadgeAttribute(): string
    {
        return match ($this->type) {
            'general'    => '<span class="badge bg-secondary">General</span>',
            'deadline'   => '<span class="badge bg-danger">Deadline</span>',
            'evaluation' => '<span class="badge bg-warning text-dark">Evaluation</span>',
            'schedule'   => '<span class="badge bg-info">Schedule</span>',
            default      => '<span class="badge bg-secondary">' . ucfirst($this->type) . '</span>',
        };
    }
}

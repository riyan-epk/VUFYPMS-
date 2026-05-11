<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'vu_id',
        'phone', 'profile_photo', 'department', 'designation', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    public function isAdmin(): bool    { return $this->role === 'admin'; }
    public function isSupervisor(): bool { return $this->role === 'supervisor'; }
    public function isStudent(): bool  { return $this->role === 'student'; }

    public function teamMember()
    {
        return $this->hasOne(TeamMember::class, 'student_id');
    }

    public function team()
    {
        return $this->hasOneThrough(Team::class, TeamMember::class, 'student_id', 'id', 'id', 'team_id');
    }

    public function supervisedTeams()
    {
        return $this->hasMany(Team::class, 'supervisor_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function userNotifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'evaluator_id');
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class, 'supervisor_id');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }

    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo) {
            return asset('storage/profiles/' . $this->profile_photo);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=1e3a5f&color=fff&size=128';
    }

    public function getUnreadNotificationsCountAttribute(): int
    {
        return $this->userNotifications()->where('is_read', false)->count();
    }
}

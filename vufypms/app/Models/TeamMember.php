<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = ['team_id', 'student_id', 'role', 'status', 'joined_at'];

    protected $casts = ['joined_at' => 'datetime'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function isLeader(): bool
    {
        return $this->role === 'leader';
    }
}

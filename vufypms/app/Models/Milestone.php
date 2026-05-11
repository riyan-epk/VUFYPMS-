<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    protected $fillable = ['semester_id', 'name', 'description', 'due_date', 'order_index'];

    protected $casts = ['due_date' => 'date'];

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function teamMilestones()
    {
        return $this->hasMany(TeamMilestone::class);
    }

    public function isOverdue(): bool
    {
        return now()->toDateString() > $this->due_date->toDateString();
    }
}

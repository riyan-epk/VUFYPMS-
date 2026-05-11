<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $fillable = ['name', 'start_date', 'end_date', 'proposal_start', 'proposal_end', 'is_active'];

    protected $casts = [
        'start_date'     => 'date',
        'end_date'       => 'date',
        'proposal_start' => 'date',
        'proposal_end'   => 'date',
        'is_active'      => 'boolean',
    ];

    public function milestones()
    {
        return $this->hasMany(Milestone::class)->orderBy('order_index');
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function current()
    {
        return static::where('is_active', true)->first();
    }

    public function isProposalOpen(): bool
    {
        $today = now()->toDateString();
        return $this->proposal_start && $this->proposal_end
            && $today >= $this->proposal_start->toDateString()
            && $today <= $this->proposal_end->toDateString();
    }
}

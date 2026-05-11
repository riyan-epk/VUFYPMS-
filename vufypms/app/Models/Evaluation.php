<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'team_id', 'evaluator_id', 'type', 'marks', 'max_marks',
        'remarks', 'recommendations', 'evaluation_date',
    ];

    protected $casts = ['evaluation_date' => 'date'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function getPercentageAttribute(): ?float
    {
        if ($this->marks === null || $this->max_marks == 0) return null;
        return round(($this->marks / $this->max_marks) * 100, 1);
    }

    public function getGradeAttribute(): string
    {
        $pct = $this->percentage;
        if ($pct === null) return 'N/A';
        return match (true) {
            $pct >= 90 => 'A+',
            $pct >= 85 => 'A',
            $pct >= 80 => 'A-',
            $pct >= 75 => 'B+',
            $pct >= 70 => 'B',
            $pct >= 65 => 'B-',
            $pct >= 60 => 'C+',
            $pct >= 55 => 'C',
            $pct >= 50 => 'D',
            default    => 'F',
        };
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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    protected $fillable = [
        'team_id', 'uploaded_by', 'type', 'original_name',
        'file_path', 'file_size', 'version', 'status', 'notes',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getDownloadUrlAttribute(): string
    {
        return route('student.documents.download', $this->id);
    }

    public function getFileSizeHumanAttribute(): string
    {
        if (!$this->file_size) return 'Unknown';
        $bytes = $this->file_size;
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }

    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            'proposal'        => 'bi-file-earmark-text text-primary',
            'srs'             => 'bi-file-earmark-code text-info',
            'design'          => 'bi-file-earmark-image text-warning',
            'progress_report' => 'bi-file-earmark-bar-graph text-success',
            'final_report'    => 'bi-file-earmark-check text-success',
            'presentation'    => 'bi-file-earmark-slides text-danger',
            default           => 'bi-file-earmark text-secondary',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'proposal'        => 'Proposal Document',
            'srs'             => 'Software Requirements Specification',
            'design'          => 'Design Document',
            'progress_report' => 'Progress Report',
            'final_report'    => 'Final Report',
            'presentation'    => 'Presentation',
            default           => 'Other Document',
        };
    }
}

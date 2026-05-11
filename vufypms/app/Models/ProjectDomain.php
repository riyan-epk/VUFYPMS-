<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDomain extends Model
{
    protected $fillable = ['name', 'description', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'domain_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

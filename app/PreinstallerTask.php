<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreinstallerTask extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at', 'team_id'];
    protected $casts = [
        'subtasks' => 'json',
    ];
    
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
    
    public function referenceInterval()
    {
        return $this->belongsTo(ReferenceInterval::class, 'reference_interval_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at', 'team_id', 'project_id', 'marker_id'];
    
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
    
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    
    public function markers()
    {
        return $this->hasMany(Marker::class);
    }
    
    public function marker()
    {
        return $this->belongsTo(Marker::class, 'marker_id')->with('marker');
    }
    
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'marker_task');
    }
}

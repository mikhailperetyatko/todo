<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    
    protected $dates = [
        'created_at',
        'updated_at',
        'execution_date',
    ];
        
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    
    public function markers()
    {
        return $this->belongsToMany(Marker::class, 'marker_task');
    }
    
    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }
    
    public function referenceInterval()
    {
        return $this->belongsTo(ReferenceInterval::class, 'reference_interval_id');
    }
    
    public function isUserHasPermission(User $user, string $permission) : bool
    {
        if (! Task::where('id', $this->id)->exists()) return true;
        return (boolean) $this->project->team->users()->where('user_id', $user->id)->firstOrFail()->pivot->role->hasPermission($permission);
    }
    
    public function deleteWithFiles()
    {
        foreach ($this->subtasks as $subtask) {
            $subtask->deleteWithFiles();
        }
        $this->delete();
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\ReferenceInterval;
use App\Project;

class Task extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at', 'date', 'time', 'marker_id'];
    
    protected $dates = [
        'created_at',
        'updated_at',
        'execution_date',
    ];
    
    protected $attributes = [
        'repeatability' => false,
    ];
    
    protected $casts = [
        'repeatability' => 'boolean',
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
        return $this->hasMany(Subtask::class)->with('tags');
    }
    
    public function referenceInterval()
    {
        return $this->belongsTo(ReferenceInterval::class, 'reference_interval_id');
    }
    
    public function isUserHasPermission(User $user, string $permission) : bool
    {
        if (! Task::where('id', $this->id)->exists()) return true;
        return (boolean) $this->project->members()->where('user_id', $user->id)->firstOrFail()->pivot->role->hasPermission($permission);
    }
    
    public function deleteWithFiles()
    {
        foreach ($this->subtasks as $subtask) {
            $subtask->deleteWithFiles();
        }
        $this->delete();
    }
        
    public function setIntervalAttribute($value)
    {
        if ($this->repeatability) $this->referenceInterval()->associate(ReferenceInterval::where('value', $value)->firstOrFail());
    }
    
    public function setDateTimeAttribute(Carbon $value)
    {
        $this->execution_date = $this->strict_date ? $value : getFirstWorkDay($value);
    }
    
    public function setStrictDateAttribute($value)
    {
        $this->strict_date = $value;
    }
    
    public function setSubtasksAttribute($subtasks)
    {
        $this->score = 0;
        foreach ($subtasks as $subtask) {
            $this->score += $subtask['score'] ?? 1;
        }
    }
    
    public function setRepeatabilityAttribute($value)
    {
        $this->attributes['repeatability'] = (bool) $value;
    }
}

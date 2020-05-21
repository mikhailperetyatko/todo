<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Pivots\ProjectUser;

class Project extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
    
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id')->withPivot('role_id')->using(ProjectUser::class);
    }
    
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    
    public function markers()
    {
        return $this->hasMany(Marker::class);
    }
    
    public function subtasks()
    {
        return $this->hasManyThrough(Subtask::class, Task::class); 
    }
    
    public function storages()
    {
        return $this->belongsToMany(Storage::class, 'project_storage');
    }
    
    public function getAllStorages()
    {
        return auth()->user()->storages->merge($this->storages)->unique();
    }
}

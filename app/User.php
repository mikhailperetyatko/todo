<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Pivots\TeamUser;

class User extends Authenticatable
{
    use Notifiable, HasRolesAndPermissions;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function isAdmin()
    {
        return false;
    }
    
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_user')->using(TeamUser::class);
    }
    
    public function storages()
    {
        return $this->hasMany(Storage::class, 'owner_id');
    }
    
    public function days()
    {
        return $this->hasMany(Day::class, 'owner_id');
    }
    
    public function teamsHasDays()
    {
        return $this->belongsToMany(Team::class, 'days_team_user');
    }
    
    public function getProjects()
    {
        return Project::whereIn('team_id', $this->teams->pluck('id'));
    }
    
    public function projects()
    {
        return $this->hasManyThrough(
            Project::class,
            TeamUser::class,
            'user_id',
            'team_id',
            'id',
            'team_id'
        );
    }
    
    public function preinstallerTasks()
    {
        return $this->hasManyThrough(
            PreinstallerTask::class,
            TeamUser::class,
            'user_id',
            'team_id',
            'id',
            'team_id'
        );
    }
    
    public function getTasks()
    {
        return Task::join('projects', 'tasks.project_id', 'projects.id')
            ->join('team_user', 'team_user.team_id', 'projects.team_id')
            ->where('team_user.user_id', $this->id)
        ;
    }
    
    public function getSubtasks()
    {
        return Subtask::select('subtasks.*')
            ->join('tasks', 'subtasks.task_id', 'tasks.id')
            ->join('projects', 'tasks.project_id', 'projects.id')
            ->join('team_user', 'team_user.team_id', 'projects.team_id')
            ->where('team_user.user_id', $this->id)
        ;
    }
    
}

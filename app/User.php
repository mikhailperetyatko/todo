<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Pivots\TeamUser;
use App\Pivots\ProjectUser;
use App\Jobs\ResetPassword;

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
            ProjectUser::class,
            'user_id',
            'id',
            'id',
            'project_id'
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
            ->join('project_user', 'project_user.project_id', 'projects.id')
            ->where('project_user.user_id', $this->id)
        ;
    }
    
    public function getSubtasks()
    {
        return Subtask::select('subtasks.*')
            ->join('tasks', 'subtasks.task_id', 'tasks.id')
            ->join('projects', 'tasks.project_id', 'projects.id')
            ->join('project_user', 'project_user.project_id', 'projects.id')
            ->where('project_user.user_id', $this->id)
        ;
    }
    
    public function sendPasswordResetNotification($token)
    {
        ResetPassword::dispatch($token, $this);
    }
}

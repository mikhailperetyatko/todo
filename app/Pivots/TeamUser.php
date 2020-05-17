<?php
    
namespace App\Pivots;

use App\User;
use App\Team;
use App\Role;
use App\Project;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamUser extends Pivot {
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    
    public function projects()
    {
        return $this->hasManyThrough(Project::class, Team::class);
    }
    
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
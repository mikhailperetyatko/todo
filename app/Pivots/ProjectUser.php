<?php
    
namespace App\Pivots;

use App\User;
use App\Team;
use App\Role;
use App\Project;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProjectUser extends Pivot {
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Pivots\TeamUser;

class Team extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    
    public function users()
    {
        return $this->belongsToMany(User::class, 'team_user')->withPivot('role_id')->using(TeamUser::class);
    }
    
    public function invitedToTeam()
    {
        return $this->hasMany(InviteTeam::class);
    }
    
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
    
    public function usersWithDays()
    {
        return $this->belongsToMany(User::class, 'days_team_user', 'team_id', 'user_id');
    }
}

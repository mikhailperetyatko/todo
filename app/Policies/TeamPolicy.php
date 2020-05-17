<?php

namespace App\Policies;

use App\User;
use App\Team;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;
        
    /**
     * Determine whether the user can view any teams.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return false;
    }
    
    public function index()
    {
        return auth()->check();
    }
    
    public function userSubtasks(User $user, Team $team)
    {
        return $team->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can view the team.
     *
     * @param  \App\User  $user
     * @param  \App\Team  $team
     * @return mixed
     */
    public function view(User $user, Team $team)
    {
        return $user->teams->contains('id', $team->id);
    }
    
    public function invite(User $user, Team $team)
    {
        return $user->id == $team->owner_id;
    }
    
    public function changeOwner(User $user, Team $team)
    {
        return $user->id == $team->owner_id;
    }
    
    public function assignOwner(User $user, Team $team)
    {
        return $user->id == $team->owner_id;
    }

    /**
     * Determine whether the user can create teams.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return auth()->check();
    }

    /**
     * Determine whether the user can update the team.
     *
     * @param  \App\User  $user
     * @param  \App\Team  $team
     * @return mixed
     */
    public function update(User $user, Team $team)
    {
        return $user->id == $team->owner_id;
    }

    /**
     * Determine whether the user can delete the team.
     *
     * @param  \App\User  $user
     * @param  \App\Team  $team
     * @return mixed
     */
    public function delete(User $user, Team $team)
    {
        return $user->id == $team->owner_id;
    }

    /**
     * Determine whether the user can restore the team.
     *
     * @param  \App\User  $user
     * @param  \App\Team  $team
     * @return mixed
     */
    public function restore(User $user, Team $team)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the team.
     *
     * @param  \App\User  $user
     * @param  \App\Team  $team
     * @return mixed
     */
    public function forceDelete(User $user, Team $team)
    {
        //
    }
}

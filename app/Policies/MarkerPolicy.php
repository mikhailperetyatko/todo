<?php

namespace App\Policies;

use App\User;
use App\Marker;
use Illuminate\Auth\Access\HandlesAuthorization;

class MarkerPolicy
{
    use HandlesAuthorization;
    
    protected function userInTeam(User $user, Marker $marker)
    {
        return $marker->team->users()->where('user_id', $user->id)->exists();
    }
    
    /**
     * Determine whether the user can view any markers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the marker.
     *
     * @param  \App\User  $user
     * @param  \App\Marker  $marker
     * @return mixed
     */
    public function view(User $user, Marker $marker)
    {
        return ! $marker->team_id || $this->userInTeam($user, $marker);
    }

    /**
     * Determine whether the user can create markers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return auth()->check();
    }

    /**
     * Determine whether the user can update the marker.
     *
     * @param  \App\User  $user
     * @param  \App\Marker  $marker
     * @return mixed
     */
    public function update(User $user, Marker $marker)
    {
        if (! $marker->team_id) return false;
        return $this->userInTeam($user, $marker);
    }

    /**
     * Determine whether the user can delete the marker.
     *
     * @param  \App\User  $user
     * @param  \App\Marker  $marker
     * @return mixed
     */
    public function delete(User $user, Marker $marker)
    {
        if (! $marker->team_id) return false;
        return $this->userInTeam($user, $marker);
    }

    /**
     * Determine whether the user can restore the marker.
     *
     * @param  \App\User  $user
     * @param  \App\Marker  $marker
     * @return mixed
     */
    public function restore(User $user, Marker $marker)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the marker.
     *
     * @param  \App\User  $user
     * @param  \App\Marker  $marker
     * @return mixed
     */
    public function forceDelete(User $user, Marker $marker)
    {
        //
    }
}

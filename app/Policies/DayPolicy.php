<?php

namespace App\Policies;

use App\User;
use App\Day;
use Illuminate\Auth\Access\HandlesAuthorization;

class DayPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any days.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the day.
     *
     * @param  \App\User  $user
     * @param  \App\Day  $day
     * @return mixed
     */
    public function view(User $user, Day $day)
    {
        return $user->id == $day->owner->id;
    }
    
    public function index(User $user)
    {
        return auth()->check();
    }

    /**
     * Determine whether the user can create days.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return auth()->check();
    }

    /**
     * Determine whether the user can update the day.
     *
     * @param  \App\User  $user
     * @param  \App\Day  $day
     * @return mixed
     */
    public function update(User $user, Day $day)
    {
        return $user->id == $day->owner->id;
    }

    /**
     * Determine whether the user can delete the day.
     *
     * @param  \App\User  $user
     * @param  \App\Day  $day
     * @return mixed
     */
    public function delete(User $user, Day $day)
    {
        return $user->id == $day->owner->id;
    }

    /**
     * Determine whether the user can restore the day.
     *
     * @param  \App\User  $user
     * @param  \App\Day  $day
     * @return mixed
     */
    public function restore(User $user, Day $day)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the day.
     *
     * @param  \App\User  $user
     * @param  \App\Day  $day
     * @return mixed
     */
    public function forceDelete(User $user, Day $day)
    {
        //
    }
}

<?php

namespace App\Policies;

use App\User;
use App\PreinstallerTask;
use Illuminate\Auth\Access\HandlesAuthorization;

class PreinstallerTaskPolicy
{
    use HandlesAuthorization;
    
    protected function userInTeam(User $user, PreinstallerTask $preinstallerTask)
    {
        return $preinstallerTask->team ? $preinstallerTask->team->users()->where('user_id', $user->id)->exists() : false;
    }
    
    /**
     * Determine whether the user can view any preinstaller tasks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the preinstaller task.
     *
     * @param  \App\User  $user
     * @param  \App\PreinstallerTask  $preinstallerTask
     * @return mixed
     */
    public function view(User $user, PreinstallerTask $preinstallerTask)
    {
        return ! $preinstallerTask->team_id || $this->userInTeam($user, $preinstallerTask);
    }

    /**
     * Determine whether the user can create preinstaller tasks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return auth()->check();
    }
    
    public function index(User $user)
    {
        return auth()->check();
    }

    /**
     * Determine whether the user can update the preinstaller task.
     *
     * @param  \App\User  $user
     * @param  \App\PreinstallerTask  $preinstallerTask
     * @return mixed
     */
    public function update(User $user, PreinstallerTask $preinstallerTask)
    {
        if (! $preinstallerTask->team_id) return false;
        return $this->userInTeam($user, $preinstallerTask);
    }

    /**
     * Determine whether the user can delete the preinstaller task.
     *
     * @param  \App\User  $user
     * @param  \App\PreinstallerTask  $preinstallerTask
     * @return mixed
     */
    public function delete(User $user, PreinstallerTask $preinstallerTask)
    {
        if (! $preinstallerTask->team_id) return false;
        return $this->userInTeam($user, $preinstallerTask);
    }

    /**
     * Determine whether the user can restore the preinstaller task.
     *
     * @param  \App\User  $user
     * @param  \App\PreinstallerTask  $preinstallerTask
     * @return mixed
     */
    public function restore(User $user, PreinstallerTask $preinstallerTask)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the preinstaller task.
     *
     * @param  \App\User  $user
     * @param  \App\PreinstallerTask  $preinstallerTask
     * @return mixed
     */
    public function forceDelete(User $user, PreinstallerTask $preinstallerTask)
    {
        //
    }
}

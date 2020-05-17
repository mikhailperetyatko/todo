<?php

namespace App\Policies;

use App\User;
use App\File;
use App\Subtask;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
    use HandlesAuthorization;
    
    protected function userInTeam(User $user, File $file)
    {
        return $file->subtask->task->project->team->users()->where('user_id', $user->id)->exists();
    }
    
    /**
     * Determine whether the user can view any files.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }
    
    public function index()
    {
        // 
    }

    /**
     * Determine whether the user can view the file.
     *
     * @param  \App\User  $user
     * @param  \App\File  $file
     * @return mixed
     */
    public function view(User $user, File $file)
    {
        return $user->id == $file->owner->id || $this->userInTeam($user, $file);
    }

    /**
     * Determine whether the user can create files.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return auth()->check();
    }

    /**
     * Determine whether the user can update the file.
     *
     * @param  \App\User  $user
     * @param  \App\File  $file
     * @return mixed
     */
    public function update(User $user, File $file)
    {
        return $user->id == $file->owner->id || $file->subtask->isUserHasPermission($user, 'write');
    }

    /**
     * Determine whether the user can delete the file.
     *
     * @param  \App\User  $user
     * @param  \App\File  $file
     * @return mixed
     */
    public function delete(User $user, File $file)
    {
        return $user->id == $file->owner->id || $file->subtask->isUserHasPermission($user, 'write');
    }

    /**
     * Determine whether the user can restore the file.
     *
     * @param  \App\User  $user
     * @param  \App\File  $file
     * @return mixed
     */
    public function restore(User $user, File $file)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the file.
     *
     * @param  \App\User  $user
     * @param  \App\File  $file
     * @return mixed
     */
    public function forceDelete(User $user, File $file)
    {
        //
    }
}

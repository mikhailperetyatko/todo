<?php

namespace App\Policies;

use App\User;
use App\Subtask;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubtaskPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any subtasks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the subtask.
     *
     * @param  \App\User  $user
     * @param  \App\Subtask  $subtask
     * @return mixed
     */
    public function view(User $user, Subtask $subtask)
    {
        return $user->id == $subtask->task->owner->id || $subtask->isUserHasPermission($user, 'read');
    }

    /**
     * Determine whether the user can create subtasks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the subtask.
     *
     * @param  \App\User  $user
     * @param  \App\Subtask  $subtask
     * @return mixed
     */
    public function update(User $user, Subtask $subtask)
    {
        return $user->id == $subtask->task->owner->id || $subtask->isUserHasPermission($user, 'write');
    }
    
    public function completing(User $user, Subtask $subtask)
    {
        return $user->id == $subtask->task->owner->id || $subtask->isUserHasPermission($user, 'complete');
    }
    public function complete(User $user, Subtask $subtask)
    {
        return $user->id == $subtask->task->owner->id || $subtask->isUserHasPermission($user, 'complete');
    }
    
    public function uncompleted(User $user, Subtask $subtask)
    {
        return $user->id == $subtask->task->owner->id || $subtask->isUserHasPermission($user, 'complete');
    }
    
    public function finishing(User $user, Subtask $subtask)
    {
        return $user->id == $subtask->task->owner->id || $subtask->isUserHasPermission($user, 'finish');
    }
    
    public function finish(User $user, Subtask $subtask)
    {
        return $user->id == $subtask->task->owner->id || $subtask->isUserHasPermission($user, 'finish');
    }
    
    public function unfinished(User $user, Subtask $subtask)
    {
        return $user->id == $subtask->task->owner->id || $subtask->isUserHasPermission($user, 'finish');
    }
    
    public function delay(User $user, Subtask $subtask)
    {
        return $user->id == $subtask->task->owner->id || $subtask->isUserHasPermission($user, 'delay');
    }
    
    public function delegation(User $user, Subtask $subtask)
    {
        return $user->id == $subtask->task->owner->id || $subtask->isUserHasPermission($user, 'delegation');
    }

    /**
     * Determine whether the user can delete the subtask.
     *
     * @param  \App\User  $user
     * @param  \App\Subtask  $subtask
     * @return mixed
     */
    public function delete(User $user, Subtask $subtask)
    {
        return $user->id == $subtask->task->owner->id || $subtask->isUserHasPermission($user, 'delete');
    }

    /**
     * Determine whether the user can restore the subtask.
     *
     * @param  \App\User  $user
     * @param  \App\Subtask  $subtask
     * @return mixed
     */
    public function restore(User $user, Subtask $subtask)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the subtask.
     *
     * @param  \App\User  $user
     * @param  \App\Subtask  $subtask
     * @return mixed
     */
    public function forceDelete(User $user, Subtask $subtask)
    {
        //
    }
}

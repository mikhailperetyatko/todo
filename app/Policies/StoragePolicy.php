<?php

namespace App\Policies;

use App\User;
use App\Storage;
use Illuminate\Auth\Access\HandlesAuthorization;

class StoragePolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any storages.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return auth()->check();
    }
    
    public function extendToken(User $user, Storage $storage)
    {
        return $user->id == $storage->owner->id;
    }

    /**
     * Determine whether the user can view the storage.
     *
     * @param  \App\User  $user
     * @param  \App\Storage  $storage
     * @return mixed
     */
    public function view(User $user, Storage $storage)
    {
        return $user->id == $storage->owner->id;
    }

    /**
     * Determine whether the user can create storages.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return auth()->check();
    }

    /**
     * Determine whether the user can update the storage.
     *
     * @param  \App\User  $user
     * @param  \App\Storage  $storage
     * @return mixed
     */
    public function update(User $user, Storage $storage)
    {
        return $user->id == $storage->owner->id;
    }

    /**
     * Determine whether the user can delete the storage.
     *
     * @param  \App\User  $user
     * @param  \App\Storage  $storage
     * @return mixed
     */
    public function delete(User $user, Storage $storage)
    {
        return $user->id == $storage->owner->id;
    }

    /**
     * Determine whether the user can restore the storage.
     *
     * @param  \App\User  $user
     * @param  \App\Storage  $storage
     * @return mixed
     */
    public function restore(User $user, Storage $storage)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the storage.
     *
     * @param  \App\User  $user
     * @param  \App\Storage  $storage
     * @return mixed
     */
    public function forceDelete(User $user, Storage $storage)
    {
        //
    }
}

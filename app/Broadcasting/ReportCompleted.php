<?php

namespace App\Broadcasting;

use App\User;

class ReportCompleted
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\User  $user
     * @return array|bool
     */
    public function join(User $user, User $initUser)
    {
        return $user->isAdmin() && $user->id == $initUser->id;
    }
}

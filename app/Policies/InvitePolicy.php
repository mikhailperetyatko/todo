<?php

namespace App\Policies;

use App\User;
use App\InviteTeam;

use Illuminate\Auth\Access\HandlesAuthorization;

class InvitePolicy
{
    use HandlesAuthorization;

    public function joinToTeam(User $user, InviteTeam $invitePolicy)
    {
        return $user->email == $invitePolicy->email;
    }
}

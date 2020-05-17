<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Team;
use App\Role;
use App\InviteTeam;

class InvitesController extends Controller
{
    public function joinToTeam(InviteTeam $inviteTeam)
    {
        $this->authorize($inviteTeam);
        
        $team = $inviteTeam->team;
        $user = auth()->user();
        $role = Role::where('id', $inviteTeam->role_id)->firstOrFail();
        
        if ($team->users->contains($user)) {
            flash('warning', 'Вы уже присоединились к этой группе ранее');
        } elseif ($inviteTeam->delete()) {
            $team->users()->attach($user->id, ['role_id' => $role->id]);
            flash('success', 'Вы успешно присоединились к группе "' . $team->name . '" по ссылке-приглашению');
        } else {
            flash('danger', 'Произошла непредвиденная ошибка. Повторите запрос позднее.');
        }
        
        return redirect('/home/teams');
    }
}

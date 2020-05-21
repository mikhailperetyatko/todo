<?php

namespace App\Http\Controllers;

use App\Team;
use App\User;
use App\Role;
use App\InviteTeam;
use Illuminate\Http\Request;
use App\Subtask;

class TeamsController extends Controller
{    
    protected function generateGUID() 
    {
        static $array = [];
        do {
            $guid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
        } while (in_array($guid, $array));
        $array[] = $guid;
        return $guid;
    }
    
    protected function getValidateRules()
    {
        return [
            'name' => 'string|required|min:5|max:100',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize(Team::class);
        $user = auth()->user();
        $teams = request()->input('only_my') ? $user->teams->where('owner_id', $user->id) : $user->teams;
        return view('home.teams.index', compact('teams'));
    }
    
    public function userSubtasks(Team $team, User $user)
    {
        $this->authorize($team);
        return view('home.teams.info', [
            'team' => $team,
            'user' => $user,
            'projects' => Subtask
                ::join('tasks', 'tasks.id', '=', 'subtasks.task_id')
                ->join('projects', 'projects.id', '=', 'tasks.project_id')
                ->join('teams', 'teams.id', '=', 'projects.team_id')
                ->select('subtasks.*', 'projects.id as projectId', 'projects.name as projectName')
                ->where(function($query) use($user, $team){
                    $query
                        ->where(function($query) use($user){
                            $query
                                ->where('executor_id', $user->id)
                                ->orWhere('validator_id', $user->id)
                            ;
                        })
                        ->where([
                            ['teams.id', $team->id],
                            ['completed', false]
                        ])
                    ;
                })->get()->groupBy('projectId')
            ,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize(new Team);
        return view('home.teams.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize(new Team);
        $user = auth()->user();
        $attr = request()->validate($this->getValidateRules());
        
        $attr['owner_id'] = $user->id;
        
        $team = Team::create($attr);
        
        $team->users()->attach($user->id, ['role_id' => Role::where('slug', 'director')->firstOrFail()->id]);
        
        flash('success');
        return redirect('/home/teams');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        $this->authorize($team);
        return view('home.teams.show', compact('team'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team)
    {
        $this->authorize($team);
        $roles = Role::all();
        
        return view('home.teams.edit', compact('team', 'roles'));
    }
    
    public function changeOwner(Team $team)
    {
        $this->authorize($team);
        
        return view('home.teams.owner', compact('team'));
    }
    
    public function assignOwner(Request $request, Team $team)
    {
        $this->authorize($team);
        $newOwner = $team->users()->where('id', $request->input('owner'))->firstOrFail();

        $team->owner_id = $newOwner->id;
        $team->save();
        
        $team->users()->detach($newOwner->id);
        $team->users()->attach($newOwner->id, ['role_id' => Role::where('slug', 'director')->firstOrFail()->id]);
        
        flash('success', 'Данные сохранены');
        return redirect('/home/teams');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        $this->authorize($team);

        $attr = $request->validate([
            'members.*.email' => 'required|string|email|max:255',
            'members.*.role' => 'required|integer',
            'news.*.email' => 'string|email|max:255',
            'news.*.role' => 'required|integer',
            'name' => 'string|required|min:5|max:100',
        ]);

        $team->name = $attr['name'];
        $team->save();
        
        $ownerExists = false;
        $owner = $team->owner;
        $teamUsers = $team->users;
        $roles = Role::all();
        
        $members = [];
        
        foreach ($team->projects as $project) {
            $members[$project->id] = [];
            foreach ($project->members()->whereIn('email', array_column($request->members, 'email'))->get() as $member) {
                $members[$project->id][$member->id] = ['role_id' => $member->pivot->role->id];
            }
            $project->members()->detach();
        }
        $team->users()->detach();
        foreach ($request->members as $member) {
            $user = $teamUsers->where('email', $member['email'])->first();
            $role = $roles->where('id', $member['role'])->first();
            
            if ($user->id == $owner->id) $ownerExists = true;
            
            if ($user->id != $owner->id) {
                $team->users()->attach($user->id, ['role_id' => $role->id]);
            }
        }
        $team->users()->attach($owner->id, ['role_id' => Role::where('slug', 'director')->firstOrFail()->id]);
        
        foreach ($team->projects as $project) {
            $project->members()->sync($members[$project->id]);
        }
        
        $team->invitedToTeam()->delete();
        if ($request->news) $this->invite($request->news, $team);
        
        if (!$ownerExists) {
            flash('danger', 'В обязательном порядке владелец должен присутствовать в команде и быть руководителем');
            return back();
        }
        
        flash('success', 'Данные сохранены');
        
        return redirect('/home/teams');
    }
    
    protected function invite(array $news, Team $team)
    {
        $this->authorize($team);
        
        foreach ($news as $new) {
            if (! $team->invitedToTeam()->where('email', $new['email'])->exists()) $team->invitedToTeam()->create([
                'email' => $new['email'],
                'guid' => $this->generateGUID(),
                'role_id' => Role::where('id', $new['role'])->firstOrFail()->id,
                'user_id' => auth()->user()->id,
            ]);
        }
        
        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        $this->authorize($team);
        
        $team->delete();
        
        flash('warning', 'Группа удалена');
        return redirect('/home/teams');
    }
}

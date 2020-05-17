<?php

namespace App\Http\Controllers;

use App\Project;
use App\User;
use App\Team;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize(Project::class);
        $user = auth()->user();
        $onlyCurrent = $request->input('onlyCurrent');
        $onlyCompleted = $request->input('onlyCompleted');
        $onlyMy = $request->input('onlyMy');
        $projects = $user->projects();
        
        if ($onlyMy) $projects->where('owner_id', $user->id);
        if (! ($onlyCompleted && $onlyCurrent)) {
            if ($onlyCompleted) $projects->where('is_old', true);
            if ($onlyCurrent) $projects->where('is_old', false);    
        }
        $projects = $projects->get();
        
        return  view('home.projects.index', compact('projects', 'user', 'onlyCurrent', 'onlyCompleted', 'onlyMy'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize(Project::class);
        return view('home.projects.create', ['teams' => auth()->user()->teams]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $team = $user->teams()->where('id', $request->input('team'))->firstOrFail();

        $attr = request()->validate([
            'name' => 'string|required|min:5|max:100',
        ]);
        
        $attr['owner_id'] = $user->id;
        $attr['team_id'] = $team->id;

        Project::create($attr);
        
        flash('success');
        return redirect('/home/projects');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        $this->authorize($project);
        return view('home.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $this->authorize($project);
        
        $teams = auth()->user()->teams;
        
        return view('home.projects.edit', compact('project', 'teams'));
    }
    
    public function setOld(Project $project)
    {
        $this->authorize($project);
        
        $project->update(['is_old' => true]);
        
        flash('success');
        return redirect('/home/projects');
    }
    
    public function setResume(Project $project)
    {
        $this->authorize($project);
        
        $project->update(['is_old' => false]);
        
        flash('success');
        return redirect('/home/projects');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $this->authorize($project);
        
        $user = auth()->user();
        $team = $user->teams()->where('id', $request->input('team'))->firstOrFail();

        $attr = request()->validate([
            'name' => 'string|required|min:5|max:100',
        ]);
        
        $project->team()->associate($team);
        $project->update($attr);
        
        flash('success');
        return redirect('/home/projects');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $this->authorize($project);
        
        $project->delete();
        
        flash('warning', 'Проект удален');
        return redirect('/home/projects');
    }
}

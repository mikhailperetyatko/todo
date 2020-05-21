<?php

namespace App\Http\Controllers;

use App\Marker;
use App\Team;
use App\Project;
use Illuminate\Http\Request;

class MarkerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Project $project)
    {
        return Marker::with('markers')->where([['team_id', $project->team->id], ['marker_id', null]])->orWhere('team_id', null)->get()->toJson();
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    
    protected function assign(Request $request, Project $project, Marker $marker)
    {   
        $user = auth()->user();
        $attr = $request->validate([
            'name' => 'required|min:3|max:255',
            'description' => 'nullable|min:3|max:255',
            'marker_id' => 'nullable|integer',
        ]);
        if ($project->members()->where('user_id', $user->id)->exists()) {
            $marker->project()->associate($project);
            $marker->team()->associate($project->team);
        }
        
        if (isset($attr['marker_id'])) {
            $referMarker = Marker::findOrfail($attr['marker_id']);
            if ($referMarker->team_id === null || $referMarker->team->users()->where('user_id', $user->id)->exists()) $marker->marker()->associate($referMarker);
        }
                
        $marker->name = $attr['name'];
        $marker->description = $attr['description'] ?? '';

        $marker->save();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Project $project)
    {
        $this->authorize(Marker::class);
        $this->assign($request, $project, new Marker);
        
        return json_encode(['result' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Marker  $marker
     * @return \Illuminate\Http\Response
     */
    public function show(Marker $marker)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Marker  $marker
     * @return \Illuminate\Http\Response
     */
    public function edit(Marker $marker)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Marker  $marker
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project, Marker $marker)
    {
        $this->authorize($marker);
        $this->assign($request, $project, $marker);
        
        return json_encode(['result' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Marker  $marker
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project, Marker $marker)
    {
        $this->authorize($marker);
        $marker->delete();
    }
}

<?php

namespace App\Http\Controllers;

use App\History;
use App\Task;
use App\Subtask;
use App\Team;
use App\Marker;
use App\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $attr = $request->validate([
            'from' => 'date',
            'to' => 'date',
            'project' => 'integer|nullable',
        ]);
        
        $user = auth()->user();
        $from = $request->input('from') ? Carbon::parse($attr['from']) : Carbon::now()->startOfMonth();
        $to = $request->input('to') ? Carbon::parse($attr['to']) : Carbon::now()->endOfMonth();
        
        $projects = $user->projects();
        if (isset($attr['project'])) $projects->whereIn('id', [$attr['project']]);
        $projects = $projects->pluck('id');
        
        $historySubtasks = History::whereIn('project_id', $projects)->whereDate('executed_date', '>=', $from->format('Y-m-d'))->whereDate('executed_date', '<=', $to->format('Y-m-d'))->get();
        
        $markers = [];
        
        foreach (Marker::whereNull('marker_id')->get() as $marker) {
            $markers[$marker->name] = $historySubtasks->filter(function ($item, $key) use ($marker) {
                $json = json_decode($item->markers);
                if (isset($json[0]) && ($json[0]->marker_id == $marker->id || $json[0]->id == $marker->id)) return true;
            });
        }
        
        foreach ($markers as $key => $marker) {
            $markers[$key] = $marker->groupBy('project_id')->map(function($item, $key) {
                return $item->groupBy(function($item, $key) {
                    return json_decode($item->markers)[0]->name;
                })->map(function($item, $key){
                    return $item->groupBy('task_id');
                });
            });
        }
        
        $markers['Вне категорий'] = $historySubtasks->filter(function ($item, $key) {
                if ($item->markers == '[]') return true;
            })->groupBy('project_id')->map(function($item, $key){
                    return $item->groupBy('task_id');
                })
        ;
        $projectsName = $user->projects->pluck('name', 'id');
        return view('home.history.index', compact('user', 'markers', 'from', 'to', 'projectsName'));
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\History  $history
     * @return \Illuminate\Http\Response
     */
    public function show(History $history)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\History  $history
     * @return \Illuminate\Http\Response
     */
    public function edit(History $history)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\History  $history
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, History $history)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\History  $history
     * @return \Illuminate\Http\Response
     */
    public function destroy(History $history)
    {
        //
    }
}

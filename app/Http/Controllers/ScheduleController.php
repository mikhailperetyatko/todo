<?php

namespace App\Http\Controllers;

use App\Task;
use App\Subtask;
use App\Team;
use App\Project;
use Carbon\Carbon;

use Illuminate\Http\Request;

class ScheduleController extends Controller
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
            'to' => 'date'
        ]);
        
        $user = auth()->user();
        $from = $request->input('from') ? Carbon::parse($attr['from']) : Carbon::now()->startOfWeek();
        $to = $request->input('to') ? Carbon::parse($attr['to']) : Carbon::now()->endOfWeek();
        
        $subtasks = $user->getSubtasks()
            ->where('subtasks.finished', 0)
            ->where(function ($query) use ($from, $to) {
                $query
                    ->where(function ($query) use ($from, $to) {
                        $query
                            ->where(function ($query) use ($from, $to) {
                                $query
                                    ->whereBetween('subtasks.showable_date', [$from->format('Y-m-d'), $to->format('Y-m-d')])
                                    //->orWhere('subtasks.execution_date', '>=', $to->endOfDay()->format('Y-m-d H:i:s'))
                                ;
                            })
                            ->where('subtasks.execution_date', '>=', $to->endOfDay()->format('Y-m-d H:i:s'))
                        ;
                    })
                    ->orWhereBetween('subtasks.execution_date', [$from->startOfDay()->format('Y-m-d H:i:s'), $to->endOfDay()->format('Y-m-d H:i:s')])
                    ->orWhere('subtasks.execution_date', '<', Carbon::now()->startOfDay()->format('Y-m-d H:i:s'))
                ;
            })
            ->orderBy('subtasks.execution_date')
        ;
        if ($request->input('executor')) $subtasks->where('executor_id', $user->id);
        if ($request->input('validator')) $subtasks->where('validator_id', $user->id);
        
        return view('home.schedule.index', [
            'subtasks' => $subtasks->get(),
            'from' => $from,
            'to' => $to,
            'dateNow' => Carbon::now()->startOfDay(),
            'dateRange' => generateDateRange(parseDate($from->format('Y-m-d')), parseDate($to->format('Y-m-d'))),
            'onlyExecutor' => $request->input('executor'),
            'onlyValidator' => $request->input('validator'),
            'showableNotNeed' => $request->input('showable_not_need'),
            'overdueNotNeed' => $request->input('overdue_not_need'),
        ]);
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
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Schedule $schedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        //
    }
}

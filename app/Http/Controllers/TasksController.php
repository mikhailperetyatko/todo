<?php

namespace App\Http\Controllers;

use App\Task;
use App\Project;
use App\Subtask;
use App\Marker;
use App\ReferenceInterval;
use App\ReferenceDifficulty;
use App\ReferencePriority;
use App\PreinstallerTask;
use Carbon\Carbon;

use Illuminate\Http\Request;

class TasksController extends Controller
{
    protected function getReference(string $name = null)
    {
        static $store = [];
        if (! $store) {
            $store = 
            [
                'difficulty' => ReferenceDifficulty::all(),
                'interval' => ReferenceInterval::all(),
                'priority' => ReferencePriority::all(),
            ];
        }

        return $name ? $store[$name] : $store;
    }
    
    protected function getDate(Subtask $subtask, Task $task)
    {
        return getDateFromInterval($subtask->referenceInterval->value, $subtask->delay, $task->execution_date);
    }
    
    protected function getValidateRules()
    {
        return [
            'name' => 'required|min:3|max:255',
            'date' => 'date_format:"Y-m-d"|required',
            'time' => 'date_format:"H:i"|required',
            'strict_date' => 'in:"on"',
            'interval_value' => 'integer|min:0',
            'repeatability' => 'in:"on"',
            'marker_id' => 'integer|nullable',
            'interval' => ['regex:/^(' . $this->getReference('interval')->implode('value', '|') . ')$/'],
            'subtasks.*.description' => 'string|max:65535',
            'subtasks.*.delay' => 'integer|nullable',
            'subtasks.*.nav' => ['regex:/^(datetime|delay)$/'],
            'subtasks.*.date' => 'date_format:"Y-m-d"|nullable',
            'subtasks.*.time' => 'date_format:"H:i"|nullable',
            'subtasks.*.showable_by' => 'integer|nullable',
            'subtasks.*.delay_interval' => ['regex:/^(' . $this->getReference('interval')->implode('value', '|') . ')$/'],
            'subtasks.*.difficulty' => ['regex:/^(' . $this->getReference('difficulty')->implode('value', '|') . ')$/'],
            'subtasks.*.priority' => ['regex:/^(' . $this->getReference('priority')->implode('value', '|') . ')$/'],
            'subtasks.*.score' => 'integer|nullable|min:0',
            'subtasks.*.tags.*' => 'integer',
            'subtasks.*.location' => 'string|nullable',
            'subtasks.*.not_delayable' => 'in:"on"',
            'subtasks.*.id' => 'integer|nullable',
            'subtasks.*.user_executor' => 'integer|nullable',
            'subtasks.*.user_validator' => 'integer|nullable',
            'subtasks' => 'required|array',
        ];
    }
    
    protected function assign(Request $request, Project $project, Task $task = null)
    {
        $user = auth()->user();
        $attr = $request->validate($this->getValidateRules());
        if (!$task) {
            $task = new Task;
            $task->owner()->associate($user);
        }
        
        $attr['date_time'] = parseDate($attr['date'] . ' ' . $attr['time']);
        $task->fill($attr);
        $task->project()->associate($project);
        $task->save();

        $task->markers()->sync(Marker::where(function($query) use ($project){
            $query
                ->where('team_id', $project->team->id)
                ->orWhereNull('team_id')
            ;
        })->where('id', $attr['marker_id'])->pluck('id'));
        
        $subtasks = $task->subtasks()->pluck('id');
        
        foreach ($attr['subtasks'] as $subtaskInput) {
            $subtask = isset($subtaskInput['id']) ? Subtask::findOrFail($subtaskInput['id']) : new Subtask;
            $subtask->task()->associate($task);
            if ($subtaskInput['nav'] == 'datetime') {
                $subtaskInput['date_time'] = isset($subtaskInput['date']) || isset($subtaskInput['time'])
                    ? Carbon::parse(($subtaskInput['date'] ?? $task->execution_date->format('Y-m-d')) . ' ' . ($subtaskInput['time'] ?? $task->execution_date->format('H:i')))
                    : $task->execution_date
                ;
                if ($subtaskInput['delay'] === null) {
                    $subtaskInput['delay'] = Carbon::parse($task->execution_date)->diffInDays(Carbon::parse($subtaskInput['date_time']->format('Y-m-d')), false);
                    $subtaskInput['delay_interval'] = 'day';
                }
            } elseif($subtaskInput['nav'] == 'delay' && ! isset($subtaskInput['delay'])) $subtaskInput['date_time'] = $task->execution_date;
            $subtask->fill($subtaskInput);
            
            $subtask->save();
            $subtask->tags()->sync($user->tags()->whereIn('id', $subtaskInput['tags'] ?? [])->get());
            if (isset($subtaskInput['id'])) $subtasks->splice($subtasks->search($subtask->id), 1);
        }
        
        $subtasks->each(function ($item) {
            Subtask::findOrFail($item)->delete();
        });
        
        return $task;

    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Project $project)
    {
        $tasks = null !== $request->input('need_all')
            ? auth()->user()->getTasks()->get()
            : $project->tasks
        ; 
        return auth()->check() 
            ? view('home.tasks.index', [
                'tasks' => $tasks,
                'need_all' => null !== $request->input('need_all'),
                'project' => $project,
            ]) 
            : redirect('/login');
    }
    
    public function chooseProject()
    {
        return auth()->check() ? view('home.tasks.choose', [
            'projects' => auth()->user()->projects()->where('is_old', false)->get(),
        ]) : redirect('/login');
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Project $project)
    {
        $this->authorize(Task::class);
        $selects = [
            'intervals' => ReferenceInterval::all(),
            'difficulties' => ReferenceDifficulty::all(),
            'priorities' => ReferencePriority::all(),
        ];
        $preinstallerTasks = PreinstallerTask::whereIn('team_id', auth()->user()->teams()->pluck('id'))->orWhereNull('team_id')->get();
        return view('home.tasks.create', compact('project', 'selects', 'preinstallerTasks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Project $project)
    {
        $this->authorize(Task::class);
        
        $task = $this->assign($request, $project);
        
        flash('success');
        return redirect('/home/projects/' . $project->id . '/tasks/' . $task->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, Task $task)
    {
        $this->authorize($task);
        
        return view('home.tasks.show', compact('project', 'task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project, Task $task)
    {
        $this->authorize($task);
        $task->subtasks = $task->subtasks;
        $selects = [
            'intervals' => ReferenceInterval::all(),
            'difficulties' => ReferenceDifficulty::all(),
            'priorities' => ReferencePriority::all(),
        ];
        $preinstallerTasks = PreinstallerTask::whereIn('team_id', auth()->user()->teams()->pluck('id'))->orWhereNull('team_id')->get();
        return view('home.tasks.edit', compact('project', 'selects', 'task', 'preinstallerTasks'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project, Task $task)
    {
        $this->authorize($task);
                
        $this->assign($request, $project, $task);
                
        flash('success');
        return redirect('/home/projects/' . $project->id . '/tasks/' . $task->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Project $project, Task $task)
    {
        $this->authorize($task);
        
        $task->deleteWithFiles();
        
        flash('warning', 'Мероприятие удалено');
        return redirect('/home/projects/' . $project->id . '/tasks');
    }
}

<?php

namespace App\Http\Controllers;

use App\PreinstallerTask;
use App\ReferenceDifficulty;
use App\ReferenceInterval;
use App\ReferencePriority;
use App\Team;
use App\User;
use Illuminate\Http\Request;

class PreinstallerTaskController extends Controller
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
    
    protected function getValidateRules()
    {
        return [
            'name' => 'required|min:3|max:255',
            'team' => 'integer|required',
            'intervalValue' => 'integer|min:0',
            'repeatability' => 'in:"on"',
            'interval' => ['regex:/^(' . $this->getReference('interval')->implode('value', '|') . ')$/'],
            'subtasks.*.description' => 'string|max:65535',
            'subtasks.*.delay' => 'integer|nullable',
            'subtasks.*.showable_by' => 'integer|nullable',
            'subtasks.*.delay_interval' => ['regex:/^(' . $this->getReference('interval')->implode('value', '|') . ')$/'],
            'subtasks.*.difficulty' => ['regex:/^(' . $this->getReference('difficulty')->implode('value', '|') . ')$/'],
            'subtasks.*.priority' => ['regex:/^(' . $this->getReference('priority')->implode('value', '|') . ')$/'],
            'subtasks.*.score' => 'integer|nullable|min:0',
            'subtasks.*.location' => 'string|nullable',
            'subtasks.*.not_delayable' => 'in:"on"',
            'subtasks' => 'required|array',
        ];
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize(PreinstallerTask::class);
        
        return view('home.preinstaller_tasks.index', [
            'tasks' => PreinstallerTask::whereIn('team_id', auth()->user()->teams()->pluck('id'))->orWhereNull('team_id')->get(),
        ]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize(PreinstallerTask::class);
        
        $selects = [
            'intervals' => ReferenceInterval::all(),
            'difficulties' => ReferenceDifficulty::all(),
            'priorities' => ReferencePriority::all(),
        ];
        $teams = auth()->user()->teams;
        
        return view('home.preinstaller_tasks.create', compact('selects', 'teams'));
    }
    
    protected function assign(Request $request, $task)
    {
        $attr = $request->validate($this->getValidateRules());
        $task->team()->associate(auth()->user()->teams()->where('id', $attr['team'])->firstOrFail());
        
        $task->repeatability = isset($attr['repeatability']);
        if (isset($attr['repeatability'])) {
            $task->interval_value = $attr['intervalValue'];
            $task->referenceInterval()->associate($this->getReference('interval')->where('value', $attr['interval'])->first());
        }
        $task->name = $attr['name'];
        
        $subtasks = [];
        
        foreach ($attr['subtasks'] as $key => $subtaskInput) {
            $subtasks[] = [
                'description' => $subtaskInput['description'],
                'showable_by' => $subtaskInput['showable_by'] ?? 0,
                'reference_interval_id' => ReferenceInterval::where('value', $subtaskInput['delay_interval'] ?? 'minute')->firstOrFail()->id,
                'delay' => $subtaskInput['delay'] ?? 0,
                'reference_difficulty_id' => ReferenceDifficulty::where('value', $subtaskInput['difficulty'] ?? 'low')->firstOrFail()->id,
                'reference_priority_id' => ReferencePriority::where('value', $subtaskInput['priority'] ?? 'low')->firstOrFail()->id,
                'score' => $subtaskInput['score'] ?? 1,
                'not_delayable' => isset($subtaskInput['not_delayable']),
            ];
        }
        $task->subtasks = $subtasks;
        $task->save();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize(PreinstallerTask::class);
        $this->assign($request, new PreinstallerTask);
        
        flash('success');
        return redirect('/home/preinstaller_tasks');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PreinstallerTask  $preinstallerTask
     * @return \Illuminate\Http\Response
     */
    public function show(PreinstallerTask $preinstallerTask)
    {
        $this->authorize($preinstallerTask);
        
        return view('home.preinstaller_tasks.show', [
            'preinstallerTask' => $preinstallerTask,
            'difficulty' => ReferenceDifficulty::all(),
            'interval' => ReferenceInterval::all(),
            'priority' => ReferencePriority::all(),
        
        ]); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PreinstallerTask  $preinstallerTask
     * @return \Illuminate\Http\Response
     */
    public function edit(PreinstallerTask $preinstallerTask)
    {
        $this->authorize($preinstallerTask);
        
        $selects = [
            'intervals' => ReferenceInterval::all(),
            'difficulties' => ReferenceDifficulty::all(),
            'priorities' => ReferencePriority::all(),
        ];
        $teams = auth()->user()->teams;
        
        return view('home.preinstaller_tasks.edit', compact('selects', 'teams', 'preinstallerTask'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PreinstallerTask  $preinstallerTask
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PreinstallerTask $preinstallerTask)
    {
        $this->authorize($preinstallerTask);
        $this->assign($request, $preinstallerTask);
        
        flash('success');
        return redirect('/home/preinstaller_tasks');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PreinstallerTask  $preinstallerTask
     * @return \Illuminate\Http\Response
     */
    public function destroy(PreinstallerTask $preinstallerTask)
    {
        $this->authorize($preinstallerTask);
        $preinstallerTask->delete();
        flash('warning', 'Предустановленная задача удалена успешно');
        return redirect('/home/preinstaller_tasks');
    }
}

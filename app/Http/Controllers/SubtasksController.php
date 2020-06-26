<?php

namespace App\Http\Controllers;

use App\Subtask;
use App\Task;
use App\Project;
use App\File;
use Illuminate\Http\Request;
use App\ReferenceInterval;
use App\Acceptance;
use App\History;
use App\ReferenceDifficulty;
use App\ReferencePriority;
use Carbon\Carbon;
use App\Jobs\DeleteFile;

class SubtasksController extends Controller
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

    protected function getValidateRules($isComplete = false)
    {
        $rules = [
            'description' => 'string|min:3|max:65535',
            'delay' => 'integer|nullable',
            'showable_by' => 'integer|nullable',
            'delay_interval' => ['regex:/^(' . $this->getReference('interval')->implode('value', '|') . ')$/'],
            'difficulty' => ['regex:/^(' . $this->getReference('difficulty')->implode('value', '|') . ')$/'],
            'priority' => ['regex:/^(' . $this->getReference('priority')->implode('value', '|') . ')$/'],
            'score' => 'integer|nullable|min:0',
            'location' => 'string|nullable',
            'not_delayable' => 'in:"on"',
            'user_executor' => 'integer|nullable',
            'user_validator' => 'integer|nullable',
            'executor_report' => 'string|max:65535|nullable',
            'repeat' => 'in:"on"',
            'subtasks_repeat' => 'array',
            'subtasks_repeat.*' => 'integer',
            'date_repeat' => 'date_format:"Y-m-d"|nullable',
            'time_repeat' => 'date_format:"H:i"|nullable'
        ];
        if (! $isComplete) {
            $rules['date'] = 'date_format:"Y-m-d"|nullable';
            $rules['time'] = 'date_format:"H:i"|nullable';
        } else {
            $rules['date'] = 'date_format:"Y-m-d"|required';
            $rules['time'] = 'date_format:"H:i"|required';
        }
        
        return $rules;
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Project $project, Task $task)
    {
        $subtask = new Subtask;
        $subtask->task_id = $task->id;
        $this->authorize($subtask);

        $users = $subtask->task->project->members;
        $intervals = ReferenceInterval::all();
        $difficulties = ReferenceDifficulty::all();
        $priorities = ReferencePriority::all();
        return view('home.subtasks.create', compact('subtask', 'users', 'intervals', 'difficulties', 'priorities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Project $project, Task $task)
    {
        $subtask = new Subtask;
        $subtask->task()->associate($task);
        
        $this->authorize($subtask);
        
        $attr = $request->validate($this->getValidateRules(true));
        $attr['showable_by'] = $attr['showable_by'] ?? 0;
        $attr['not_delayable'] = isset($attr['not_delayable']);
        if (isset($attr['date']) && isset($attr['time'])) $attr['execution_at'] = $attr['date'] . ' ' . $attr['time'] . ':00';
        $attr['showable_at'] = $attr['showable_by'];
        $attr['score'] = $attr['score'] ?? 1;
 
        // проверка прав        
        unset($attr['date']);
        unset($attr['time']);
        
        $subtask->fill($attr);
        $subtask->save();
        
        flash('success');
        return redirect('/home/projects/' . $project->id . '/tasks/' . $task->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subtask  $subtask
     * @return \Illuminate\Http\Response
     */
    public function show(Subtask $subtask)
    {
        $this->authorize($subtask);
        return view('home.subtasks.show', compact('subtask'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subtask  $subtask
     * @return \Illuminate\Http\Response
     */
    public function edit(Subtask $subtask)
    {
        $this->authorize($subtask);
        $users = $subtask->task->project->members;
        $intervals = ReferenceInterval::all();
        $difficulties = ReferenceDifficulty::all();
        $priorities = ReferencePriority::all();
        
        return view('home.subtasks.edit', compact('subtask', 'users', 'intervals', 'difficulties', 'priorities'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subtask  $subtask
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subtask $subtask)
    {
        $this->authorize($subtask);
        
        $attr = $request->validate($this->getValidateRules());
        $attr['not_delayable'] = isset($attr['not_delayable']);
        if (isset($attr['date']) && isset($attr['time'])) $attr['execution_at'] = $attr['date'] . ' ' . $attr['time'] . ':00';
        $attr['showable_at'] = $attr['showable_by'];
 
        // проверка прав        
        unset($attr['date']);
        unset($attr['time']);
        
        $subtask->update($attr);
        
        flash('success');
        return redirect('/home/subtasks/' . $subtask->id);
        
    }
    
    public function completing(Subtask $subtask)
    {
        $this->authorize($subtask);
        return view('home.subtasks.complete', compact('subtask'));
    }
    
    protected function repeatSubtask(Subtask $subtask, string $datetime)
    {
        $subtaskRepeat = (new Subtask);
        $repeatAttr = $subtask->toArray();
        unset($repeatAttr['task']);
        unset($repeatAttr['validator']);
        $repeatAttr['completed'] = false;
        $subtaskRepeat->fill($repeatAttr);
        $subtaskRepeat->execution_at = $datetime;
        $subtaskRepeat->executor()->associate($subtask->executor);
        $subtaskRepeat->validator()->associate($subtask->validator);

        $subtaskRepeat->save();
    }
    
    public function complete(Request $request, Subtask $subtask)
    {
        $this->authorize($subtask);
        
        if ($subtask->completed)  {
            flash('warning', 'Задача уже выполнена ранее');
            return back();
        }
        
        $attr = $request->validate($this->getValidateRules(! $subtask->task->repeatability && $request->input('repeat')));
        
        $files = $request->validate(['files.*' => 'integer|nullable']);
        $subtask->completed = true;
        
        $acceptance = new Acceptance;
        $acceptance->executor()->associate(auth()->user());
        $acceptance->validator()->associate($subtask->validator);
        $acceptance->subtask()->associate($subtask);
        $acceptance->executor_report = $attr['executor_report'];
        $acceptance->report_date = Carbon::now()->format('Y-m-d H:i:s');
        
        if ($attr['date_repeat'] || $attr['time_repeat']) {
            $this->repeatSubtask($subtask, $attr['date_repeat'] . ' ' . $attr['time_repeat'] . ':00');
            unset($attr['date_repeat']);
            unset($attr['time_repeat']);
        }
        
        if (auth()->user()->id == $subtask->validator->id) {
            $acceptance->validator()->associate(auth()->user());
            $acceptance->annotation_date = Carbon::now()->format('Y-m-d H:i:s');
            $acceptance->validator_annotation = 'Автоматическое завершение при выполнении задачи принимающей стороной';
            $subtask->finished = true;
            $this->attachSubtaskIntoHistory($subtask);
        }
        
        $acceptance->save();
        File::syncFilesWithModel($acceptance, $files['files'] ?? []);
        $subtask->save();
        
        flash('success');
        $this->needRepeat($subtask, $attr);
        
        return redirect('/home/schedule');
    }
    
    public function uncompleted(Request $request, Subtask $subtask)
    {
        $this->authorize($subtask);
        
        if (! $subtask->completed)  {
            flash('warning', 'Задача еще не выполнена');
            return back();
        }
        $subtask->update([
            'completed' => false,
        ]);
        $subtask->acceptance()->last()->delete();
        
        flash('success');
        return redirect('/home/schedule');
    }
    
    public function finishing(Subtask $subtask)
    {
        $this->authorize($subtask);
        
        return view('home.subtasks.finish', compact('subtask'));
    }
    
    public function finish(Request $request, Subtask $subtask)
    {
        $this->authorize($subtask);
        
        if ($subtask->finished)  {
            flash('warning', 'Задача уже завершена ранее');
            return back();
        }
        
        $dateAndTimeRequired = ! $subtask->task->repeatability && $request->input('repeat');
        
        $attr = $request->validate([
            'validator_annotation' => 'string|max:65535|' . ($request->input('decision') == 'refuse' ? 'required' : 'nullable'),
            'decision' => ['regex:/^(refuse|accept)$/', 'required'],
            'date' => $dateAndTimeRequired ? 'date_format:"Y-m-d"|required' : '',
            'time' => $dateAndTimeRequired ? 'date_format:"H:i"|required' : '',
            'subtasks_repeat' => 'array',
            'subtasks_repeat.*' => 'integer',
            'repeat' => 'in:"on"',
        ]);
        
        $files = $request->validate(['files.*' => 'integer|nullable']);
        
        $acceptance = $subtask->acceptances()->latest()->first();
        $acceptance->validator()->associate(auth()->user());
        $acceptance->validator_annotation = $attr['validator_annotation'];
        $acceptance->annotation_date = Carbon::now()->format('Y-m-d H:i:s');
        
        if ($attr['decision'] == 'accept') {
            $subtask->finished = true;
            
        } else {
            $subtask->completed = false;
        }
        
        $acceptance->save();
        $subtask->save();
        File::syncFilesWithModel($acceptance, $files['files'] ?? []);
        $this->attachSubtaskIntoHistory($subtask);
        
        flash('success');
        
        if ($subtask->finished) $this->needRepeat($subtask, $attr);
                
        return redirect('/home/schedule');
    }
    
    public function unfinished(Request $request, Subtask $subtask)
    {
        $this->authorize($subtask);
        
        if (! $subtask->finished)  {
            flash('warning', 'Задача еще не завершена');
            return back();
        }
        
        $subtask->update([
            'finished' => false,
        ]);
        
        flash('success');
        return redirect('/home/schedule');
    }
    
    protected function needRepeat(Subtask $subtask, array $attr)
    {
        if (! $subtask->task->subtasks()->where('finished', 0)->count()) {
            if (isset($attr['repeat']) && $attr['repeat']) {
                $task = $this->repeatTask($subtask->task, $attr, $subtask->task->repeatability);
                flash('success', 'Мероприятие "' . $task->name . '" назначено повторно на ' . $task->execution_date->format('d.m.Y в H:i'));
            } else {
                flash('success', 'Мероприятие "' . $subtask->task->name . '" завершено');
                $subtask->task->delete();
            }
        }
    }
    
    protected function repeatTask(Task $task, array $attr, $repeatability)
    {
        $task->execution_date = parseDate($attr['date'] . ' ' . $attr['time'] . ':00')->format('Y-m-d H:i:s');
        
        $newTask = Task::create($task->attributesToArray());
        $newTask->markers()->sync($task->markers()->pluck('id'));
        
        $subtasks = $task->subtasks;
        
        foreach($subtasks as $subtask) {
            if (in_array($subtask->id, $attr['subtasks_repeat'] ?? [])) {
                $subtask->completed = false;
                $subtask->finished = false;
                $subtask->execution_at = getDateFromInterval($subtask->referenceInterval->value, $subtask->delay ?? 0, $newTask->execution_date)->format('Y-m-d H:i:s');
                
                foreach($subtask->acceptances as $acceptance) {
                    $acceptance->delete();
                }
                
                $subtask->task()->associate($newTask);
                $subtask->save();
            }
        }
        $task->delete();
        return $newTask;
    }
    
    protected function attachSubtaskIntoHistory(Subtask $subtask)
    {
        History::create([
            'description' => $subtask->description,
            'execution_date' => $subtask->execution_date,
            'executed_date' => Carbon::now(),
            'project_id' => $subtask->task->project_id,
            'owner_id' => $subtask->task->owner_id,
            'subtask_id' => $subtask->id,
            'task_id' => $subtask->task_id,
            'task_name' => $subtask->task->name,
            'task_execution_date' => $subtask->task->execution_date,
            'executor_id' => $subtask->executor_id,
            'validator_id' => $subtask->validator_id,
            'reference_difficulty_id' => $subtask->reference_difficulty_id,
            'score' => $subtask->score,
            'location' => $subtask->location,
            'acceptances' => $subtask->acceptances()->with('files')->get()->toJson(),
            'files' => $subtask->files()->with('storage')->get()->toJson(),
            'comments' => $subtask->comments()->with('files')->get()->toJson(),
            'markers' => $subtask->task->markers()->with('marker')->get()->toJson(),
        ]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subtask  $subtask
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subtask $subtask)
    {
        $this->authorize($subtask);
        $url = '/home/projects/' . $subtask->task->project->id . '/tasks/' . $subtask->task->id;
        $subtask->deleteWithFiles();
        
        flash('danger', 'Задача удалена');
        return redirect($url);
    }
}

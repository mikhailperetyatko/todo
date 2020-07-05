<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ReferenceInterval;
use App\ReferenceDifficulty;
use App\ReferencePriority;
use Carbon\Carbon;
use App\Jobs\SubtaskNotify;
use Illuminate\Support\Str;
use App\Toasts\SubtaskCreate;
use App\Toasts\SubtaskUpdate;
use App\Jobs\DeleteFile;

class Subtask extends Model
{   
    protected $guarded = ['id', 'created_at', 'updated_at', 'executor_id', 'validator_id'];
    protected $casts = [
        'delayable' => 'boolean',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'execution_date',
        'showable_date'
    ];
    
    protected static function createdAndUpdatedEvents(Subtask $subtask, Carbon $delay = null)
    {
        $uuid = (string) Str::uuid();
        \Redis::set('subtask-job-' . $subtask->id, $uuid);
        if ($subtask->referencePriority && $subtask->referencePriority->value == 'high') {
            SubtaskNotify::dispatch($uuid, $subtask)->delay(Carbon::now()->diffInSeconds($delay ? $delay : $subtask->execution_date->addHours(config('app.delay_before_notify_about_subtask_event'))));
        }
    }
    
    public static function repeatEventTomorrow(Subtask $subtask)
    {
        self::createdAndUpdatedEvents($subtask, Carbon::now()->addDay());
    }
    
    protected static function boot()
    {
        parent::boot();
        static::created(function(Subtask $subtask){
            self::createdAndUpdatedEvents($subtask);
            toasts(
                $subtask,
                SubtaskCreate::class,
                collect([$subtask->executor, $subtask->validator]),
                [$subtask->task->owner->id]
            );
        });
        
        static::updated(function(Subtask $subtask){
            self::createdAndUpdatedEvents($subtask);
            toasts(
                $subtask,
                SubtaskUpdate::class,
                collect([$subtask->executor, $subtask->validator]),
                [$subtask->task->owner->id]
            );
        });
        
        static::deleting(function(Subtask $subtask){
            $uuid = (string) Str::uuid();
            \Redis::set('subtask-job-' . $subtask->id, $uuid);
        });
    }
    
    public function deleteWithFiles()
    {
        foreach($this->files as $file) {
            DeleteFile::dispatch($file);
        }
        $this->delete();
    }
    
    public function setShowableAtAttribute($value)
    {
        $this->showable_date = getFirstWorkDay(parseDate($this->execution_date)->subDays(abs($value)))->format('Y-m-d');
    }
    
    public function setDelayIntervalAttribute($value)
    {
        $this->referenceInterval()->associate(ReferenceInterval::where('value', $value)->firstOrFail());
    }
    
    public function setDifficultyAttribute($value)
    {
        $this->referenceDifficulty()->associate(ReferenceDifficulty::where('value', $value)->firstOrFail());
    }
    
    public function setPriorityAttribute($value)
    {
        $this->referencePriority()->associate(ReferencePriority::where('value', $value)->firstOrFail());
    }
    
    public function setUserExecutorAttribute($value)
    {
        if (policy($this)->delegation(auth()->user(), $this)) {
            $this->executor()->associate($this->task->project->members()->where('id', $value)->firstOrFail());
        }
    }
    
    public function setUserValidatorAttribute($value)
    {
        if (policy($this)->delegation(auth()->user(), $this)) {
            $this->validator()->associate($this->task->project->members()->where('id', $value)->firstOrFail());
        }
    }
    
    public function setExecutionAtAttribute($value)
    {
        if (policy($this)->delay(auth()->user(), $this)) {
            $this->execution_date = getFirstWorkDay(parseDate($value))->format('Y-m-d H:i:s');
            $this->setShowableAtAttribute($this->showable_by);
        }
    }
        
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
    
    public function acceptances()
    {
        return $this->hasMany(Acceptance::class);
    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    public function files()
    {
        return $this->hasMany(File::class);
    }
        
    public function executor()
    {
        return $this->belongsTo(User::class, 'executor_id');
    }
    
    public function validator()
    {
        return $this->belongsTo(User::class, 'validator_id');
    }
    
    public function referenceInterval()
    {
        return $this->belongsTo(ReferenceInterval::class, 'reference_interval_id');
    }
    
    public function referenceDifficulty()
    {
        return $this->belongsTo(ReferenceDifficulty::class, 'reference_difficulty_id');
    }
    
    public function referencePriority()
    {
        return $this->belongsTo(ReferencePriority::class, 'reference_priority_id');
    }
    
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'subtask_tag');
    }
    
    public function isUserHasPermission(User $user, string $permission) : bool
    {
        if (! Subtask::where('id', $this->id)->exists()) return true;
        return (boolean) $this->task->project->members()->where('id', $user->id)->firstOrFail()->pivot->role->hasPermission($permission);
    }
}

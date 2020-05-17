<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $casts = [
        'acceptances' => 'json',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'execution_date',
        'executed_date',
        'task_execution_date',
    ];
    
    public function subtask()
    {
        return $this->belongsTo(Subtask::class, 'subtask_id');
    }
    
    public function project()
    {
        return $this->belongsTo(Project::class, 'executor_id');
    }
    
    public function referenceDifficulty()
    {
        return $this->belongsTo(ReferenceDifficulty::class, 'reference_difficulty_id');
    }
    
    public function executor()
    {
        return $this->belongsTo(User::class, 'executor_id');
    }
    
    public function validator()
    {
        return $this->belongsTo(User::class, 'validator_id');
    }

}

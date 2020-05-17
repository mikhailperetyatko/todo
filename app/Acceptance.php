<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Acceptance extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at', 'executor_id', 'validator_id'];
    protected $dates = [
        'created_at',
        'updated_at',
        'report_date',
        'annotation_date'
    ];

    public function subtask()
    {
        return $this->belongsTo(Subtask::class, 'subtask_id');
    }
    
    public function executor()
    {
        return $this->belongsTo(User::class, 'executor_id');
    }
    
    public function validator()
    {
        return $this->belongsTo(User::class, 'validator_id');
    }
    
    public function files()
    {
        return $this->morphToMany(File::class, 'fileable');
    }
    
}

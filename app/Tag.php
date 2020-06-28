<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
        
    public function subtasks()
    {
        return $this->belongsToMany(Subtasks::class, 'subtask_tag');
    }
    
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}

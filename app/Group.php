<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}

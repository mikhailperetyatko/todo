<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    
    public function getRouteKeyName()
    {
        return 'name';
    }
    
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}

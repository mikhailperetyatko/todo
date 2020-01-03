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
        return $this->morphedByMany(Post::class, 'taggable');
    }
    
    public function informations()
    {
        return $this->morphedByMany(Information::class, 'taggable');
    }
    
    public static function tagsCloud()
    {
        return (new static)->has('posts')->orHas('informations')->get();
    }
}

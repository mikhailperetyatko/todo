<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $casts = [
        'owner_id' => 'integer',
    ];
    
    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
    
    public function comments()
    {
        return $this->morphToMany(Comment::class, 'commentable')->with('owner');
    }
    
    public function owner()
    {
        return $this->belongsTo(User::class);
    }
    
    public function scopePublished($query)
    {
        return $query->where('published', 1);
    }
}

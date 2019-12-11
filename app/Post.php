<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Post extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $casts = [
        'owner_id' => 'integer',
        'published' => 'boolean',
    ];
    
    protected static function boot()
    {
        parent::boot();
        static::updating(function(Post $post){
            $after = $post->getDirty();
            $post->history()->attach(auth()->id(), [
                'before' => json_encode(Arr::only($post->fresh()->toArray(), array_keys($after))),
                'after' => json_encode($after),
            ]);
        });
        static::deleting(function(Post $post) {
            $post->comments()->delete();
        });
    }
    
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
    
    public function history()
    {
        return $this->belongsToMany(User::class, 'post_histories')->withPivot(['before', 'after'])->using('App\PostHistory')->withTimestamps();
    }
}

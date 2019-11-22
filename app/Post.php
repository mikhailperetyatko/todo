<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
    
    public function owner()
    {
        return $this->belongsTo(User::class);
    }
    
    public function scopePublishedAndLatest($query)
    {
        return $query->where('published', 1)->latest();
    }
}

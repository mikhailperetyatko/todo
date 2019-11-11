<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected$dispatchesEvents = [
        'created' => \App\Events\PostCreated::class,
        'updated' => \App\Events\PostUpdated::class,
        'deleted' => \App\Events\PostDeleted::class,
    ];    
    
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
}

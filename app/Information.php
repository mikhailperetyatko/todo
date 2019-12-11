<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $table = 'informations';
    protected $casts = [
        'owner_id' => 'integer',
    ];
    
    protected static function boot()
    {
        parent::boot();
        static::deleting(function(Information $information) {
            $information->comments()->delete();
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
        return $this->morphToMany(Comment::class, 'commentable');
    }
}

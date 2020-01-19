<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

trait CommentableAndTaggableTrait
{
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
    
    public function comments()
    {
        return $this->morphToMany(Comment::class, 'commentable')->with('owner');
    }
    
    public static function bootCommentableAndTaggableTrait()
    {
        static::deleting(function ($model){
            $model->comments()->delete();
        });
    }
}
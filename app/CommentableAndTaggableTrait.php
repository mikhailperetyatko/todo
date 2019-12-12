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
    
    public static function deletingComments()
    {
        return function(self $model) {
            $model->comments()->delete();
        };
    }
}
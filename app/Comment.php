<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Toasts\CommentCreate;

class Comment extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at', 'owner_id', 'refer_comment_id'];
    protected $casts = [
        'owner_id' => 'integer',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    
    protected static function boot()
    {
        parent::boot();
        static::created(function(Comment $comment){
            toasts(
                $comment,
                CommentCreate::class,
                $comment->subtask->task->project->members,
                [$comment->owner->id]
            );
        });
    }
    
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    
    public function subtask()
    {
        return $this->belongsTo(Subtask::class, 'subtask_id');
    }
    
    public function files()
    {
        return $this->morphToMany(File::class, 'fileable');
    }
    
    public function referComment()
    {
        return $this->belongsTo(Comment::class, 'refer_comment_id')->with('owner');
    }
    
    public function setReferCommentAttribute($value)
    {
        $this->referComment()->associate($this->subtask->comments()->where('id', $value)->firstOrFail());
    }
}

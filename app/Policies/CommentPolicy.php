<?php

namespace App\Policies;

use App\User;
use App\Comment;
use App\Subtask;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;
    
    protected function userInTeam(User $user, Subtask $subtask)
    {
        return $subtask->task->project->members()->where('user_id', $user->id)->exists();
    }
    /**
     * Determine whether the user can view the subtask.
     *
     * @param  \App\User  $user
     * @param  \App\Subtask  $subtask
     * @return mixed
     */
    public function index(User $user, Subtask $subtask)
    {
        return $this->userInTeam($user, $subtask);
    }
    
    public function view(User $user, Comment $comment)
    {
        return $this->userInTeam($user, $comment->subtask);
    }
    
    /**
     * Determine whether the user can create comments.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user, Subtask $subtask)
    {
        return $this->userInTeam($user, $subtask);
    }
    
    /**
     * Determine whether the user can update the comment.
     *
     * @param  \App\User  $user
     * @param  \App\Comment  $comment
     * @return mixed
     */
    public function update(User $user, Comment $comment)
    {
        return $user->id == $comment->owner->id;
    }

    /**
     * Determine whether the user can delete the comment.
     *
     * @param  \App\User  $user
     * @param  \App\Comment  $comment
     * @return mixed
     */
    public function delete(User $user, Comment $comment)
    {
        return false;
    }
}

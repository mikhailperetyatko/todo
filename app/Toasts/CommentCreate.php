<?php
    
namespace App\Toasts;

use App\User;

class CommentCreate extends Toast
{    
    public function process()
    {        
        $this->message = view('toasts.comment', ['model' => $this->model])->render();
        $this->link = '/home/subtasks/' . $this->model->subtask->id;
        
        return $this;
    }
}
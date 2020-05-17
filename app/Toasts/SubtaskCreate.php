<?php
    
namespace App\Toasts;

use App\User;

class SubtaskCreate extends Toast
{    
    public function process()
    {
        if ($this->user->id == $this->model->executor->id) $role = 'Исполнитель';
        elseif($this->user->id == $this->model->validator->id) $role = 'Контроллер';
        else $role = 'Участник';
        
        $this->message = view('toasts.subtaskCreate', ['user' => $this->user, 'model' => $this->model, 'role' => $role])->render();
        $this->link = '/home/subtasks/' . $this->model->id;
        
        return $this;
    }
}
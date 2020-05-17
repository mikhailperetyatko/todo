<?php

namespace App\Toasts;

use Illuminate\Database\Eloquent\Model;
use App\User;

abstract class Toast
{
    public $model;
    public $user;
    public $message;
    public $link;
    
    public function __construct(Model $model, User $user)
    {
        $this->model = $model;
        $this->user = $user;
    }
    
    abstract public function process();
    
    public function getResource()
    {
        $this->process();
        
        return [
            'message' => $this->message,
            'link' => $this->link,
            'user' => $this->user,
        ];
    }
}
<?php

namespace App\Notifications;

class PushallNotification
{
    public $title;
    public $post;
    public $template;
    
    public function __construct(string $title, \App\Post $post, string $template = 'pushall.pushall-notify')
    {
        $this->title = $title;
        $this->post = $post;
        $this->template = $template;
    }
    
    public function compile()
    {
        return view($this->template, ['post' => $this->post])->render();
    }
}
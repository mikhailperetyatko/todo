<?php

namespace App\Notifications;

class PushallNotification
{
    public $title;
    public $post;
    public $template;
    public $actionName;
    
    public function __construct(string $title, \App\Post $post, string $actionName = 'Посмотреть', string $template = 'pushall.pushall-notify')
    {
        $this->title = $title;
        $this->post = $post;
        $this->template = $template;
        $this->actionName = $actionName;
    }
    
    public function compile()
    {
        return view($this->template, ['post' => $this->post])->render();
    }
    
    public function getFullUrl(string $url, callable $function)
    {
        return $function($url);
    }
    
    public function getUrlForRedirect()
    {
        return $this->getFullUrl(
            $this->post->slug, 
            function($url) 
            {
                return config('app.url') . '/posts/' . $url;
            }
        );
    }
}
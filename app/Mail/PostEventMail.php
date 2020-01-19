<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PostEventMail extends Mailable
{
    use Queueable, SerializesModels;

    public $post;
    public $event = '';
    public $showLink;
    public $mailTemplate = '';
    
    public function __construct(\App\Post $post, string $template = 'mail.task-event')
    {
        $this->post = $post;
        $this->mailTemplate = $template;
        $this->showLink = false;
    }
    
    public function withEvent(string $event)
    {
        $this->event = $event;
        return $this;
    }
    
    public function withLink()
    {
        $this->showLink = true;
        return $this;
    }

    public function build()
    {
        return $this->markdown($this->mailTemplate);
    }
}

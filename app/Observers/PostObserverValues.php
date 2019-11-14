<?php

namespace App\Observers;

class PostObserverValues
{
    public $event = '';
    public $showLink;
    public $mailTemplate = '';
    
    public function __construct(string $template = 'mail.task-event')
    {
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
}
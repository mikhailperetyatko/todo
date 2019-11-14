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
    public $values;
    
    public function __construct(\App\Post $post, \App\Observers\PostObserverValues $values)
    {
        $this->values = $values;
        $this->post = $post;
    }

    public function build()
    {
        return $this->markdown($this->values->mailTemplate);
    }
}

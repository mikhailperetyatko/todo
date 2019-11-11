<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PostEvent extends Mailable
{
    use Queueable, SerializesModels;

    public $post;
    public $event;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(\App\Events\PostEventable $event)
    {
        $this->event = $event;
        $this->post = $event->post;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.task-event');
    }
}

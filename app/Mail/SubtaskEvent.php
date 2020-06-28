<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\Subtask;

class SubtaskEvent extends Mailable
{
    use Queueable, SerializesModels;
    
    public $subtask;
    public $view;
    
    public function __construct(Subtask $subtask, string $view = 'mail.events.subtasks')
    {
        $this->subtask = $subtask;
        $this->view = $view;
        
        return $this;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from(config('app.robo_email'))
            ->subject($this->subtask->task->name)
            ->view($this->view)
            ->with([
                'subtask' => $this->subtask,
            ])
        ;
    }
}

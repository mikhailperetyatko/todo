<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\InviteTeam;

class TeamInvite extends Mailable
{
    use Queueable, SerializesModels;
    
    public $invite;
    public $view;
    
    public function __construct(InviteTeam $invite, string $view = 'mail.events.invite')
    {
        $this->invite = $invite;
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
            ->view($this->view)
            ->with([
                'subtask' => 'Предложение присоединиться к группе',
            ])
        ;
    }
}

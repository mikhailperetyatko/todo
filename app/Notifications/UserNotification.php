<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;
use App\Toasts\Toast;

class UserNotification extends Notification
{
    use Queueable;
    protected $data;

    public function __construct(Toast $toast)
    {
        $this->data = $toast->getResource();
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }
    
    public function toArray($notifiable)
    {
        return [
            'message' => $this->data['message'],
            'link' => $this->data['link'],
        ]; 
    }
}

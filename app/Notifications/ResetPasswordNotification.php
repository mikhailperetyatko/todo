<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;
use App\Mail\ResetPassword as Mailable;

class ResetPasswordNotification extends Notification
{
    use Queueable;
    protected $token;
    
    public function __construct($token)
    {
        $this->token = $token;
    }
    
    public function via($notifiable)
    {
        return ['mail'];
    }
    
    public function toMail($notifiable)
    {
        return (new Mailable($this->token, $notifiable))->subject('Восстановление пароля')->to($notifiable->email);
    }
}
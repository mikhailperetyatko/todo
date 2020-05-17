<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\User;
use App\Toasts\Toast;

class UserNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    protected $user;
    public $message;
    public $link;
    
    public function __construct(Toast $toast)
    {
        $resource = $toast->getResource();
        $this->message = $resource['message'];
        $this->user = $resource['user'];
        $this->link = $resource['link'];
    }
    
    public function broadcastOn()
    {
        return new PrivateChannel('notifications.' .  $this->user->id);
    }
}

<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class GenerateReport implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    use \App\ReportableTrait;
    public $user;
    
    public function forUser(\App\User $user)
    {
        $this->user = $user;
        return $this;
    }
    
    public function broadcastOn()
    {
        return new PrivateChannel('ReportCompleted.' .  $this->user->id);
    }
}

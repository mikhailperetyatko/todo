<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use App\Post;

class PostCreated implements PostEventable
{
    use PostEventTrait;
    const NAME = 'Создание';
    const SHOW_LINK = true;
}

<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use App\Post;

class PostUpdated implements PostEventable
{
    use PostEventTrait;
    const NAME = 'Обновление';
    const SHOW_LINK = true;
}

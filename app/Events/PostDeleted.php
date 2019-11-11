<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use App\Post;

class PostDeleted implements PostEventable
{
    use PostEventTrait;
    const NAME = 'Удаление';
    const SHOW_LINK = false;
}

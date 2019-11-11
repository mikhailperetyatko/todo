<?php

namespace App\Events;

use App\Post;

trait PostEventTrait
{
    public $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }
}

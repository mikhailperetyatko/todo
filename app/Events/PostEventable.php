<?php

namespace App\Events;

use App\Post;

interface PostEventable
{
    public function __construct(Post $post);
}
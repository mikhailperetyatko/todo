<?php

namespace App\Listeners;

use App\Events\PostEventable;
use App\Mail\PostEvent as Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPostEventNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PostEvent  $event
     * @return void
     */
    public function handle(PostEventable $event)
    {
        \Mail::to(config('app.adminEmail'))->send(new Mail($event));
    }
}

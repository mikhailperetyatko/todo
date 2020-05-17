<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('PostUpdate', \App\Broadcasting\PostUpdate::class);
Broadcast::channel('App.User.{user}', \App\Broadcasting\UserNotification::class);
<?php

use App\Services\Pushall;
use App\Services\PushallNotification;
use App\Services\Statistics;
    
if (! function_exists('flash')) {
    function flash(string $type, string $message = 'Операция прошла успешно')
    {
        session()->flash('type', $type);
        session()->flash('message', $message);
    }    
}

if (! function_exists('pushall')) {
    function pushall(PushallNotification $push = null)
    {
        if (is_null($push)) {
            return app(Pushall::class);
        } else {
            return app(Pushall::class)->send($push);
        }
    }    
}

if (! function_exists('getUrlForRedirect')) {
    function getUrlForRedirect(\App\Http\Controllers\Controller $controller, string $method, $value = null)
    {
        return action(class_basename($controller) . '@' . $method, $value);
    }    
}

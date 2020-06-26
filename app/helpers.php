<?php

use App\Services\Pushall;
use App\Services\PushallNotification;
use App\Services\Statistics;
use Carbon\Carbon;
use App\User;
use App\Notifications\UserNotification;
use App\Toasts\Toast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
    
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

if (! function_exists('rememberCacheWithTags')) {
    function rememberCacheWithTags(array $tags, string $key, callable $function, int $duration = null)
    {
        return \Cache::tags($tags)->remember($key, ($duration ?? config('cache.defaultDuration')), $function);
    }    
}

if (! function_exists('parseDate')) {
    function parseDate($date)
    {
        if ($date instanceof Carbon) return $date;
        return Carbon::parse($date);
    }    
}

if (! function_exists('generateDateRange')) {
    function generateDateRange(Carbon $start_date, Carbon $end_date, string $format = 'd.m.Y', bool $toString = true) 
    {
        $dates = []; 
        for ($date = $start_date; $date->lte($end_date); $date->addDay()) { 
            $dates[] = $toString ? $date->format($format) : parseDate($date->format($format)); 
        } 
        return $dates; 
    }    
}

if (! function_exists('isWorkDay')) {
    function isWorkDay(Carbon $date)
    {
        $teamsWithDays = auth()->user()->teams()->has('usersWithDays')->get();
        if (! $teamsWithDays->isEmpty()) {
            $day = $teamsWithDays->first()->usersWithDays->first()->days()->whereDate('date', $date)->first();
            if ($day) return ! $day->is_weekend;
        }
        if ($date->dayOfWeekIso >= 6) return false;
        return true;
    }    
}

if (! function_exists('getFirstWorkDay')) {
    function getFirstWorkDay(Carbon $date)
    {
        do {
            $isWorkDay = isWorkDay($date);
            if (! $isWorkDay) $date->subDay();
        } while (! $isWorkDay);
        return $date;
    }    
}

if (! function_exists('division')) {
    function division(int $dividend)
    {
        $array = [];
        if ($dividend < 1024) $array = ['divider' => 1, 'category' => 'b'];
        if ($dividend >= 1024 && $dividend < 1024 * 1024) $array = ['divider' => 1024, 'category' => 'Kb'];
        if ($dividend >= 1024 * 1024 && $dividend < 1024 * 1024 * 1024) $array = ['divider' => 1024 * 1024, 'category' => 'Mb'];
        if ($dividend >= 1024 * 1024 * 1024 && $dividend < 1024 * 1024 * 1024 * 1024) $array = ['divider' => 1024 * 1024 * 1024, 'category' => 'Gb'];
        if ($dividend >= 1024 * 1024 * 1024 * 1024) $array = ['divider' => 1024 * 1024 * 1024 * 1024, 'category' => 'Tb'];
        
        $array['relation'] = round(($dividend / $array['divider']), 1);
        return $array;
    }    
}

if (! function_exists('toasts')) {
    function toasts(Model $model, string $toastClass, Collection $users, array $excludedUsersId = [])
    {
        foreach ($users->unique() as $user)
        {
            if ($user instanceof User && ! in_array($user->id, $excludedUsersId)) {
                $user->notify(new UserNotification(new $toastClass($model, $user)));
            }
        }
    }    
}

if (! function_exists('getDateFromInterval')) {
    function getDateFromInterval(string $interval, int $delay, Carbon $date)
    {
        $date = Carbon::parse($date->format('Y-m-d H:i:s'));
        switch ($interval) {
            case 'minute':
            case 'hour':
            case 'day':
            case 'week':
                $method = ($delay < 0 ? 'sub' : 'add') . ucfirst($interval) . 's';
                return $date->$method(abs($delay));
            case 'month':
            case 'quarter':
            case 'year':
                return getDateFromCustomsInterval($interval, $delay, $date);
        }
    }    
}

if (! function_exists('getDateFromCustomsInterval')) {
    function getDateFromCustomsInterval(string $interval, int $delay, Carbon $date)
    {
        $day = (int) $date->format('d');
        $hours = (int) $date->format('H');
        $minutes = (int) $date->format('i');
        
        if ($interval == 'quarter') $koef = 3;
        elseif ($interval == 'year') $koef = 12;
        else $koef = 1;
        
        if ($delay > 0) {
            $date->startOfMonth()->addMonths($delay * $koef);
        } else {
            $date->startOfMonth()->subMonths($delay * $koef);
        }
        
        $lastDay = (int) Carbon::parse($date->format('Y-m-d'))->endOfMonth()->format('d');
  
        return $date->addDays($day > $lastDay ? $lastDay - 1 : $day - 1)->addHours($hours)->addMinutes($minutes);
    }    
}
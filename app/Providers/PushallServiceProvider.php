<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Pushall;

class PushallServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Pushall::class, function (){
            return new Pushall(
                config('app.pushall.api.id'), config('app.pushall.api.key'), config('app.pushall.api.uri')
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

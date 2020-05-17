<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Services\Statistics;
use App\Services\ReportToXLSX;
use App\ReportableDataHandler;
use App\Mail\ReportMail;
use App\Events\GenerateReport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        date_default_timezone_set('Europe/Moscow');        
        
        \App\Post::observe(\App\Observers\PostObserver::class);
        
        \Blade::directive('getLinkForManagePost', function ($exp) {
            return "<?php echo url((auth()->check() && auth()->user()->isAdmin() ? '/admin' : '') . '/posts/' . {$exp}->slug); ?>";
        });
        
        \Blade::directive('getLinkForManageNews', function ($exp) {
            return "<?php echo url((auth()->check() && auth()->user()->isAdmin() ? '/admin' : '') . '/informations/' . {$exp}->slug); ?>";
        });
                
        Relation::morphMap([
            'posts' => 'Post',
            'informations' => 'Information',
        ]);        
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

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
        
        view()->composer('layout.sidebar', function($view) {
            $view->with('tagsCloud', \App\Tag::tagsCloud());
        });
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
        
        app()->singleton(Statistics::class, function() {
            return new Statistics;
        });
        
        app()->bind(ReportToXLSX::class, function() {
            return new ReportToXLSX;
        });
        
        app()->bind(ReportableDataHandler::class, function() {
            return new ReportableDataHandler;
        });
        
        app()->bind(ReportMail::class, function() {
            return new ReportMail();
        });
        
        app()->bind(GenerateReport::class, function() {
            return new GenerateReport();
        });
        
        
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

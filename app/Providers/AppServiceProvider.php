<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
            $view->with('tagsCloud', \App\Tag::has('posts')->get());
        });
        \App\Post::observe(\App\Observers\PostObserver::class);
        
        \Blade::directive('prefix', function () {
            return "<?php echo auth()->check() && auth()->user()->hasRight('posts_right', 'm') ? '/admin' : ''; ?>";
            
/*
            return "<?php echo '<a href=\"' . (auth()->user()->hasRight('posts_right', 'm') ? '/admin' : '') . '/posts/' . ($exp)->slug . '/edit\" class=\"btn btn-outline-secondary btn-sm\">Изменить</a>'; ?>";
            
*/
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

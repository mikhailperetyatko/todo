<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

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
        
        \Blade::directive('getNameOfPostAttributeInRussian', function ($exp) {
            return "<?php
                switch(($exp)) {
                    case 'slug' :
                        echo 'Символьный код';
                        break;
                    case 'title' :
                        echo 'Название статьи';
                        break;
                    case 'description' :
                        echo 'Краткое описание';
                        break;
                    case 'body' :
                        echo 'Текст статьи';
                        break;
                    case 'published' :
                        echo 'Опубликован';
                        break;
                    default : 
                        echo '';
                }
            ?>";
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

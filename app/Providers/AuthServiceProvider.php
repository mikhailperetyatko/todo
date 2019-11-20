<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Post::class => \App\Policies\PostPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        Gate::define('administratePosts', function (\App\User $user) {
            return $user->hasRight('posts_right', 'm');
        });
        
        Gate::define('administrateFeedbacks', function (\App\User $user) {
            return $user->hasRight('feedbacks_right', 'm');
        });
        
        Gate::define('administrate', function (\App\User $user) {
            return $user->hasRight('feedbacks_right', 'm') || $user->hasRight('posts_right', 'm');
        });
        
/*
        Gate::define('writeToFeedbacks', function (\App\User $user) {
            return $user->hasRight('feedbacks_right', 'w');
        });
        
        Gate::define('writeToPosts', function (\App\User $user) {
            return $user->hasRight('posts_right', 'w');
        });
        
        Gate::define('writeToTags', function (\App\User $user) {
            return $user->hasRight('tags_right', 'w');
        });
*/
    }
}

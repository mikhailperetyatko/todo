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
        \App\Post::class => \App\Policies\PostPolicy::class,
        \App\Team::class => \App\Policies\TeamPolicy::class,
        \App\InviteTeam::class => \App\Policies\InvitePolicy::class,
        \App\Task::class => \App\Policies\TaskPolicy::class,
        \App\Project::class => \App\Policies\ProjectPolicy::class,
        \App\Subtask::class => \App\Policies\SubtaskPolicy::class,
        \App\Comment::class => \App\Policies\CommentPolicy::class,
        \App\File::class => \App\Policies\FilePolicy::class,
        \App\Marker::class => \App\Policies\MarkerPolicy::class,
        \App\Submarker::class => \App\Policies\SubmarkerPolicy::class,
        \App\PreinstallerTask::class => \App\Policies\PreinstallerTaskPolicy::class,
        \App\Day::class => \App\Policies\DayPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        Gate::define('administrate', function (\App\User $user) {
            return $user->isAdmin();
        });
    }
}

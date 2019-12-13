<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Statistics;
use App\User;

class StatisticsReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $models = [];
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        dd(request()->input());
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Statistics $statistics, User $user)
    {
        //\Mail::to($user->email)->send($mail);
    }
}

<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;
use App\Jobs\RefreshToken;
use App\Jobs\DumpMysqlDatabase;
use App\Jobs\GetParticipationAmount;
use App\Services\Mets;
use App\Storage;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\BackupDatabase::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
     
    protected function schedule(Schedule $schedule)
    {     
        $schedule
            ->call(function () {
                $storages = Storage::all();
                foreach ($storages as $storage){
                    \Queue::push(new RefreshToken($storage));
                }
        })->monthlyOn(1, '00:00');
        
        $schedule
            ->call(function () {
                \Queue::push(new DumpMysqlDatabase());
        })->dailyAt('20:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

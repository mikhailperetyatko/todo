<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Mail;
use App\Mail\BackupMysql;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup {filename}';
    protected $description = 'Backup the database';

    public function __construct()
    {
        parent::__construct();
    }
    
    public function handle()
    {
        try {
            (new Process(
                sprintf(
                    'mysqldump -u %s -p%s %s %s | zip > %s',
                    config('database.connections.mysql.username'),
                    config('database.connections.mysql.password'),
                    config('database.connections.mysql.database'),
                    collect(\DB::select('SHOW TABLES'))->filter(function ($value, $key) {
                        return strpos($value->Tables_in_todo, 'telescope_') === false;
                    })->implode('Tables_in_todo', ' '),
                    $this->argument('filename')
                )
            ))->mustRun();
            $this->info('The backup has been proceed successfully');
        } catch (ProcessFailedException $exception) {
            $this->error($exception->getMessage());
        }
    }
}

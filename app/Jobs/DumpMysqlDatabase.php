<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mail\BackupMysql;
use Carbon\Carbon;

class DumpMysqlDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $filename;
    
    public function __construct(string $filename = null)
    {
        $this->filename = $filename === null ? Carbon::now()->format('Y_m_d_H_i_s') : $filename;
        $this->filename = storage_path('backups/mysql/' . $this->filename . '.sql.zip');
    }
    
    public function handle()
    {
        \Artisan::call('db:backup', [
            'filename' => $this->filename,
        ]);
        Mail::to(['mihanya@list.ru'])->send(new BackupMysql($this->filename, \Artisan::output()));
        if (is_file($this->filename)) unlink($this->filename);
    }
}

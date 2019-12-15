<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\User;
use App\Mail\ReportMail;

class StatisticsReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $mail;
    
    public function __construct(array $allowedTables)
    {
        $this->mail = new ReportMail();
        
        foreach ($allowedTables as $table) {
            if (request()->input($table)) {
                $methodName = 'with' . ucfirst($table);
                $this->mail->$methodName();
            }
        }
    }
 
    public function handle()
    {
        if(! empty($this->mail->tables)) \Mail::to(auth()->user()->email)->send($this->mail);
    }
}

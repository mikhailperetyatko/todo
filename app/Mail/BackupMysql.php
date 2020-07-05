<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;

class BackupMysql extends Mailable
{
    use Queueable, SerializesModels;
    
    public $filename;
    public $commandResponse;
    public $view;
    
    public function __construct(string $filename, string $commandResponse, string $view = 'mail.backups.mysql')
    {
        $this->filename = $filename;
        $this->view = $view;
        $this->commandResponse = $commandResponse;
        
        return $this;
    }
    
    public function build()
    {   
        $file_exists = is_file($this->filename);
        $this
            ->from(config('app.robo_email'))
            ->subject('Бэкап от ' . Carbon::now()->format('d.m.Y'))
            ->view($this->view)
            ->with(['commandResponse' => $this->commandResponse, 'file_exists' => $file_exists])
        ;
        if ($file_exists) $this->attach($this->filename);
        
        return $this;
    }
}

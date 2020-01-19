<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\Statistics;

class ReportMail extends Mailable
{
    use Queueable, SerializesModels;
    use \App\ReportableTrait;
    
    protected $mailTemplate;
    
    public function withTemplate(string $template)
    {
        $this->mailTemplate = $template;
        return $this;
    }
    
    public function withDefaultTemplate()
    {
        $this->withTemplate('mail.report');
        return $this;
    }
        
    public function build()
    {
        return $this->markdown($this->mailTemplate);
    }
}

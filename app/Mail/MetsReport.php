<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;

class MetsReport extends Mailable
{
    use Queueable, SerializesModels;

    public $view;
    public $state;
    public $lot;

    public function __construct(string $lot, array $state, string $view = 'mail.mets_report')
    {
        $this->view = $view;
        $this->lot = $lot;
        $this->state = $state;

        return $this;
    }

    public function build()
    {
        $this
            ->from(config('app.robo_email'))
            ->subject('Отчет проверки METS по лоту №"' . $this->lot . '"')
            ->view($this->view)
            ->with([
                'state' => $this->state,
            ])
        ;

        return $this;
    }
}

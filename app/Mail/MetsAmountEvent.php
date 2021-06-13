<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;

class MetsAmountEvent extends Mailable
{
    use Queueable, SerializesModels;

    public $view;
    public $amount;
    public $lot;

    public function __construct(string $amount, string $lot, string $view = 'mail.mets_event')
    {
        $this->view = $view;
        $this->amount = $amount;
        $this->lot = $lot;

        return $this;
    }

    public function build()
    {
        $this
            ->from(config('app.robo_email'))
            ->subject('Изменение количества заявок METS по лоту №' . $this->lot)
            ->view($this->view)
            ->with([
                'lot' => $this->lot,
                'amount' => $this->amount,
            ])
        ;

        return $this;
    }
}

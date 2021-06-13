<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Services\Mets;
use App\Mail\MetsAmountEvent;
use App\Mail\MetsReport;
use Carbon\Carbon;

class GetParticipationAmount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;
    protected $lot;
    protected $login;
    protected $password;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $opts)
    {
        $this->login = $opts['login'];
        $this->password = $opts['password'];
        $this->id = $opts['id'];
        $this->lot = $opts['lot'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $result = (new Mets($this->login, $this->password))->auth()->getParticipationAmount($this->id, $this->lot);
        $amount = $result['amount'];
        $name = $result['name'];
        $start = Carbon::now();
        $state = json_decode(\Redis::get('mets_state_' . $this->id . '_' . $this->lot), true, JSON_UNESCAPED_UNICODE);
                
        if (\Redis::get('mets_' . $this->id . '_' . $this->lot) !== $amount) {
            \Redis::set('mets_' . $this->id . '_' . $this->lot, $amount);
            Mail::to(['mihanya@list.ru', 'au_chebykin@mail.ru'])->send(new MetsAmountEvent($amount, $this->lot . ' (' . $name . ')'));
        }
        if ($state && Carbon::parse($state[0]['start'])->diffInDays($start, true) > 0) {
            Mail::to(['mihanya@list.ru', 'au_chebykin@mail.ru'])->send(new MetsReport($this->lot, $state));
            $state = [];
        }
        
        $state[] = [
            'start' => $start->format('d.m.Y H:i:s'),
            'amount' => $amount,
        ];
        \Redis::set('mets_state_' . $this->id . '_' . $this->lot, json_encode($state));
    }
}

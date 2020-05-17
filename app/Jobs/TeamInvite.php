<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\User;
use App\InviteTeam;
use App\Mail\TeamInvite as MailTeamInvite;

class TeamInvite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $invite;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(InviteTeam $invite)
    {
        $this->invite = $invite;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Mail::to($this->invite->email)->send(new MailTeamInvite($this->invite));
    }
}

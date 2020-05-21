<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Subtask;
use Carbon\Carbon;
use App\Mail\SubtaskEvent;

class SubtaskNotify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $subtaskId;
    protected $uuid;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $uuid, Subtask $subtask)
    {
        $this->subtaskId = $subtask->id;
        $this->uuid = $uuid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subtask = Subtask::findOrFail($this->subtaskId);
        if ($subtask->finished) return;
        $now = Carbon::now();
        $uuid = \Redis::get('subtask-job-' . $subtask->id);
        if ($uuid !== NULL && $uuid !== $this->uuid) return;
        
        $subtask->repeatEventTomorrow($subtask);
        
        \Mail::to(collect([$subtask->executor, $subtask->validator])->unique())->send(new SubtaskEvent($subtask));
    }
}

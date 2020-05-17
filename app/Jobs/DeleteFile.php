<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use App\File;

class DeleteFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $service;
    protected $token;
    protected $uuid;
    protected $projectId;
    protected $taskId;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(File $file)
    {
        $this->service = $file->storage->service;
        $this->token = $file->storage->token;
        $this->uuid = $file->uuid;
        $this->projectId = $file->subtask->task->project->id;
        $this->taskId = $file->subtask->task->id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $service = $this->service; 
        (new $service([
            'token' => $this->token,
            'uuid' => $this->uuid,
            'projectId' => $this->projectId,
            'taskId' => $this->taskId,
        ]))->remove();
    }
}

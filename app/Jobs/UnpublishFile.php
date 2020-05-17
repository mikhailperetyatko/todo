<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\File;
use Illuminate\Support\Str;

class UnpublishFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $file;
    protected $uuid;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(File $file, string $uuid)
    {
        $this->file = $file;
        $this->uuid = $uuid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $uuid = \Redis::get('download-file-' . $this->file->id);
        if ($uuid !== NULL && $uuid !== $this->uuid) return;
        
        $this->file->publicly_available_until = null;
        $this->file->public_url = null;
        $this->file->save();
        
        $service = $this->file->storage->service; 
        (new $service($this->file))->closeLink();
    }
}

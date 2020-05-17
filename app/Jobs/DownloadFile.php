<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use App\File;
use App\Storage as myStorage;

class DownloadFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $storage;
    protected $file;
    protected $toStorage;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(File $file, myStorage $storage)
    {
        $this->storage = $file->storage;
        $this->toStorage = $storage;
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $service = $this->storage->service; 
        (new $service($this->file))->download()->remove();
        $this->file->uploaded = false;
        $this->file->storage()->associate($this->toStorage);
        $this->file->save();
    }
}

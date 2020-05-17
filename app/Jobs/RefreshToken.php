<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Storage;
use Carbon\Carbon;

class RefreshToken implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $storageId;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Storage $storage)
    {
        $this->storageId = $storage->id;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $storage = Storage::findOrFail($this->storageId);
        $now = Carbon::now();
        $request = $storage->service::refreshToken($storage);
        if ($request->access_token) {
            $storage->token = $request->access_token;
            $storage->refresh_token = $request->refresh_token;
            $storage->token_expires_at = $now->addSeconds($request->expires_in);
            $storage->save();
        } else {
            abort(500);
        }
    }
}

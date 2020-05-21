<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Jobs\RemoveTempFile;
use App\Jobs\DeleteFile;
use App\Jobs\UploadFile;
use Carbon\Carbon;

class File extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at', 'owner_id', 'subtask_id', 'storageable_id'];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    
    protected static function boot()
    {
        parent::boot();
        static::created(function(File $file){
            UploadFile::dispatch($file);
            RemoveTempFile::dispatch($file->uuid)->delay(config('app.delayBeforeDeleteFile'));
        });
        
        static::updated(function(File $file){
            if (! $file->uploaded) {
                UploadFile::dispatch($file);
                RemoveTempFile::dispatch($file->uuid)->delay(config('app.delayBeforeDeleteFile'));
            }
        });
    }
    
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    
    public function subtask()
    {
        return $this->belongsTo(Subtask::class, 'subtask_id');
    }
    
    public function storage()
    {
        return $this->belongsTo(Storage::class, 'storage_id');
    }
    
    public function comments()
    {
        return $this->morphedByMany(Comment::class, 'fileable');
    }
    
    public function acceptances()
    {
        return $this->morphedByMany(Acceptance::class, 'fileable');
    }
    
    public static function syncFilesWithModel(Model $model, array $files)
    {
        $filesToSync = [];
        
        foreach ($files as $file) {
            $fileModel = File::findOrFail($file);
            if ($fileModel->subtask->task->project->members()->where('user_id', auth()->user()->id)->exists()) $filesToSync[] = $file;
        }
        $model->files()->sync($filesToSync);
    }
    

}

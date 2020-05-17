<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use App\Storage;
use Illuminate\Support\Facades\Storage as LaravelStorage;
use App\File;
use Arhitector\Yandex\Disk;
use Arhitector\Yandex\Client\OAuth;
use Illuminate\Support\Str;
use App\Jobs\UnpublishFile;

class FileYandexDisk implements FileInterface
{
    
    protected $disk;
    protected $file;
    protected $filename;
    protected $path;
    
    public function __construct(File $file)
    {
        $this->file = $file;
        $this->disk = new Disk(new OAuth($file->storage->token));
        
        $filename = explode('/', $this->file->uuid);
        $filename = end($filename);
        
        $this->filename = $filename;
        $this->path = config('app.yandexDiskResource') . '/' . $this->file->subtask->task->project->id . '_' . $this->file->subtask->task->id;
        
        return $this;
    }
    
    protected static function requestToApi(array $postdata, string $url, string $method = 'POST')
    {
        $postdata = http_build_query($postdata);
        
        $opts = array('http' =>
            [
                'method'  => $method,
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            ]
        );
        
        $context = stream_context_create($opts);
        
        try {
            $result = file_get_contents($url, false, $context);
        } catch(\Exception $e) {
            abort(500);
        }
        return json_decode($result);
    }
    
    public static function getToken(string $code)
    {
        return self::requestToApi(
            [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'client_id' => config('app.yandexDiskAccount'),
                'client_secret' => config('app.yandexDiskPassword'),
            ], 
            'https://oauth.yandex.ru/token'
        );
    }
    
    public static function refreshToken(Storage $storage)
    {
        return self::requestToApi(
            [
                'grant_type' => 'refresh_token',
                'refresh_token' => $storage->refresh_token,
                'client_id' => config('app.yandexDiskAccount'),
                'client_secret' => config('app.yandexDiskPassword'),
            ], 
            'https://oauth.yandex.ru/token'
        );
    }
        
    public function link()
    {
        $resource =  $this->disk->getResource($this->path . '/' . $this->filename);
        
        $uuid = (string) Str::uuid();
        \Redis::set('download-file-' . $this->file->id, $uuid);
        
        dispatch(new UnpublishFile($this->file, $uuid))->delay(config('app.enablePublicLink'));
        
        return $resource->setPublish(true)->public_url;
    }
    
    public function closeLink()
    {
        $resource =  $this->disk->getResource($this->path . '/' . $this->filename);
        $resource->setPublish(false);
    }
    
    public function upload()
    {
        if (! $this->disk->getResource($this->path)->has()) $this->disk->getResource($this->path)->create();
        $resource =  $this->disk->getResource($this->path . '/' . $this->filename);
        $resource->upload(LaravelStorage::path($this->file->uuid), true);
        
        return $this;
    }
    
    public function download()
    {
        $resource =  $this->disk->getResource($this->path . '/' . $this->filename);
        $tempfile = config('app.localStorageDir') . '/' . $this->filename;
        $resource->download($tempfile, true, true);
        if (LaravelStorage::exists('uploads/' . $this->filename)) LaravelStorage::delete('uploads/' . $this->filename);
        LaravelStorage::move('public/' . $this->filename, 'uploads/' . $this->filename);
        
        return $this;
    }
    
    public function remove()
    {
        $resource =  $this->disk->getResource($this->path . '/' . $this->filename);
        $resource->delete();
        
        return $this;
    }
    
    public static function freeSpace(string $token)
    {
        $disk = new Disk(new OAuth($token));
        
        return [
            'free' => $disk['free_space'],
            'total' => $disk['total_space'],
        ];
    }
    
    public static function freeSpaceWithFormat(string $token)
    {
        $space = static::freeSpace($token);
        $resultFree = division($space['free']);
        $resultTotal = division($space['total']);
                
        return [
            'free' => $resultFree['relation'] . ' ' . $resultFree['category'],
            'total' => $resultTotal['relation'] . ' ' . $resultTotal['category'],
        ];
    }
    
    public static function division(int $dividend, string $category)
    {
        
    }
    
}

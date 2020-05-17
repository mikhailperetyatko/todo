<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use App\Storage;
use Illuminate\Support\Facades\Storage as LaravelStorage;
use App\File;
use App\Project;
use App\Task;
use Arhitector\Yandex\Disk;
use Arhitector\Yandex\Client\OAuth;
use Illuminate\Support\Str;
use App\Jobs\UnpublishFile;

class FileYandexDisk implements FileInterface
{
    
    protected $token;
    protected $uuid;
    protected $tempFolder;
    protected $projectId;
    protected $taskId;
    
    public function __construct($input)
    {
        if ($input instanceof File) {
            $resource = [
                'token' => $input->storage->token,
                'uuid' => $input->uuid,
                'projectId' => $input->subtask->task->project->id,
                'taskId' => $input->subtask->task->id,
            ];
        } else {
            $resource = $input;
        }
        
        $this->token = $resource['token'];
        
        $file = explode('/', $resource['uuid']);
        $this->uuid = array_pop($file);
        $this->tempFolder = implode('/', $file);
        
        $this->projectId = $resource['projectId'];
        $this->taskId = $resource['taskId'];
        
        return $this;
    }
    
    protected function getOAuth()
    {
        return new OAuth($this->token);
    }
    
    protected function getDisk()
    {
        static $disk;
        
        if (! $disk) $disk = new Disk($this->getOAuth());
        
        return $disk;
    }
    
    protected function getDir()
    {
        return config('app.yandexDiskResource') . '/' . $this->projectId . '_' . $this->taskId;
    }
    
    protected function getFullName()
    {
        return $this->getDir() . '/' . $this->uuid;
    }
    
    protected function getTempFullFilename()
    {
        return $this->tempFolder . '/' . $this->uuid;
    }
        
    protected function getFileResource()
    {
        return $this->getDisk()->getResource($this->getFullName());
    }
        
    public function link()
    {
        $resource =  $this->getFileResource();
        $model = File::where('uuid', $this->getTempFullFilename())->firstOrFail();
        
        $uuid = (string) Str::uuid();
        \Redis::set('download-file-' . ($model->id), $uuid);
        
        dispatch(new UnpublishFile($model, $uuid))->delay(config('app.enablePublicLink'));
        
        return $resource->setPublish(true)->public_url;
    }
    
    public function closeLink()
    {
        $this->getFileResource()->setPublish(false);
    }
    
    public function upload()
    {
        $disk = $this->getDisk();
        $dir = $this->getDir();
        
        if (! $disk->getResource($dir)->has()) $disk->getResource($dir)->create();
        $this->getFileResource()->upload(LaravelStorage::path($this->getTempFullFilename()), true);
        
        return $this;
    }
    
    public function download()
    {
        $tempfile = config('app.localStorageDir') . '/' . $this->uuid;
        $this->getFileResource()->download($tempfile, true, true);
        
        if (LaravelStorage::exists('uploads/' . $this->uuid)) LaravelStorage::delete('uploads/' . $this->uuid);
        LaravelStorage::move('public/' . $this->uuid, 'uploads/' . $this->uuid);
        
        return $this;
    }
    
    public function remove()
    {
        $this->getFileResource()->delete();
        
        $dir = $this->getDisk()->getResource($this->getDir());
        
        if ($dir->items->count() < 1) $dir->delete();
        
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
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Project;

class Storage extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at', 'owner_id'];
    protected $dates = [
        'created_at',
        'updated_at',
        'token_expires_at',
    ];
    
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    
    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }
    
    public function setTypeAttribute($value)
    {
        $this->service = $this->getStorage($value)['class'];
    }
    
    public function getUrlAttribute()
    {
        return $this->getStorage($this->service, 'class')['url'] . '/access/' . $this->id;
    }
    
    public static function getStorage($value, $key = 'value') : array
    {
        return config('app.storages')[array_search($value, array_column(config('app.storages'), $key))];
    }
    
    public function freeSpace()
    {
        $service = $this->service;
        return $service::freeSpace($this->token);
    }
    
    public function freeSpaceWithFormat()
    {
        $service = $this->service;
        return $service::freeSpaceWithFormat($this->token); 
    }
    
}

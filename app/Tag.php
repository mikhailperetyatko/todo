<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CacheFlushableAfterCreatedModelTrait;

class Tag extends Model
{
    use CacheFlushableAfterCreatedModelTrait;
    
    protected $guarded = ['id', 'created_at', 'updated_at'];
        
    public function getRouteKeyName()
    {
        return 'name';
    }
    
    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }
    
    public function informations()
    {
        return $this->morphedByMany(Information::class, 'taggable');
    }
    
    public static function tagsCloud()
    {
        return (new static)->has('posts')->orHas('informations')->get();
    }
}

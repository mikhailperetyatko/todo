<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use CommentableAndTaggableTrait;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $table = 'informations';
    protected $casts = [
        'owner_id' => 'integer',
    ];
    
    protected static function boot()
    {
        parent::boot();      
          
        static::updated(function(){
            \Cache::tags(['informations', 'information', 'statistics'])->flush();
        });
        
        static::created(function(){
            \Cache::tags(['informations', 'tags', 'statistics'])->flush();
        });
        
        static::deleted(function(){
            \Cache::tags(['informations', 'information', 'comments', 'tags', 'statistics'])->flush();
        });
    }
    
    public function getRouteKeyName()
    {
        return 'slug';
    }
}

<?php

namespace App;

trait CacheFlushableAfterCreatedModelTrait
{
    public static function bootCacheFlushableAfterCreatedModelTrait()
    {
        static::created(function (){
            \Cache::tags([self::class])->flush();
        });
    }
}
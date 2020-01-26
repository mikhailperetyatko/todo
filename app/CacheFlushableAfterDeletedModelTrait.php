<?php

namespace App;

trait CacheFlushableAfterDeletedModelTrait
{
    public static function bootCacheFlushableAfterDeletedModelTrait()
    {
        static::deleted(function (){
            \Cache::tags([self::class])->flush();
        });
    }
}
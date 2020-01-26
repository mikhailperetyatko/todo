<?php

namespace App;

trait CacheFlushableAfterUpdatedModelTrait
{
    public static function bootCacheFlushableAfterUpdatedModelTrait()
    {
        static::updated(function (){
            \Cache::tags([self::class])->flush();
        });
    }
}
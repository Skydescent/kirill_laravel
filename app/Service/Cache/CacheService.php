<?php

namespace App\Service\Cache;

use App\Contracts\Service\CacheServiceContract;
use Cache;

class CacheService implements CacheServiceContract
{

    public function forget(array $tag, string $cacheKey)
    {
        Cache::tags($tag)->forget($cacheKey);
    }

    public function flushCollections(array $collections)
    {
        Cache::tags($collections)->flush();
    }

    public function cache(
        array          $tags,
        string     $cacheKey,
        int             $ttl,
        \Closure $queryData ,
    )
    {
        return Cache::tags($tags)->remember($cacheKey,$ttl,$queryData);
    }

}
<?php

namespace App\Contracts\Service;

interface CacheServiceContract
{
    public function cache(array $tags, string $cacheKey, int $ttl, \Closure $queryData);

    public function flushCollections(array $collections);

    public function forget(array $tag, string $cacheKey);
}
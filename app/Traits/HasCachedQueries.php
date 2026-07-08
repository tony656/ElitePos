<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait HasCachedQueries
{
    protected function rememberForever($key, $callback)
    {
        return Cache::remember($key, 86400, $callback);
    }
    
    protected function remember($key, $callback, $minutes = 5)
    {
        return Cache::remember($key, $minutes * 60, $callback);
    }
    
    protected function forget($key)
    {
        Cache::forget($key);
    }
    
    protected function forgetPattern($pattern)
    {
        // This requires a cache driver that supports wildcards like Redis
        // For file/database cache, you'd need to implement differently
        if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
            $redis = Cache::getStore()->client();
            $keys = $redis->keys($pattern);
            foreach ($keys as $key) {
                Cache::forget($key);
            }
        }
    }
}
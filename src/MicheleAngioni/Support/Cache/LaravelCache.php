<?php

namespace MicheleAngioni\Support\Cache;

use Illuminate\Cache\CacheManager;

class LaravelCache implements CacheInterface
{
    /**
     * @var CacheManager
     */
    protected $cache;

    /**
     * @var int
     */
    protected $seconds;

    /**
     * Construct
     *
     * @param CacheManager $cache
     */
    public function __construct(CacheManager $cache)
    {
        $this->cache = $cache;
        $this->seconds = config('ma_support.cache_time');
    }

    /**
     * Get
     *
     * @param  string $key
     * @param  array $tags
     *
     * @return  mixed
     */
    public function get(string $key, array $tags)
    {
        return $this->cache->tags($tags)->get($key);
    }

    /**
     * Put
     *
     * @param string $key
     * @param mixed $value
     * @param array $tags
     * @param int $seconds
     *
     * @return mixed
     */
    public function put(string $key, $value, array $tags, int $seconds = null)
    {
        if (is_null($seconds)) {
            $seconds = $this->seconds;
        }

        return $this->cache->tags($tags)->put($key, $value, $seconds);
    }

    /**
     * Has
     *
     * @param string $key
     * @param array $tags
     *
     * @return bool
     */
    public function has(string $key, array $tags): bool 
    {
        return $this->cache->tags($tags)->has($key);
    }
}

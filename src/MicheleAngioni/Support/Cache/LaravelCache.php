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
    protected $minutes;

    /**
     * Construct
     *
     * @param CacheManager $cache
     */
    public function __construct(CacheManager $cache)
    {
        $this->cache = $cache;
        $this->minutes = config('ma_support.cache_time');
    }

    /**
     * Get
     *
     * @param   string $key
     * @param   array $tags
     *
     * @return  mixed
     */
    public function get($key, array $tags)
    {
        return $this->cache->tags($tags)->get($key);
    }

    /**
     * Put
     *
     * @param  string $key
     * @param  mixed $value
     * @param  array $tags
     * @param  int $minutes
     *
     * @return mixed
     */
    public function put($key, $value, array $tags, $minutes = null)
    {
        if (is_null($minutes)) {
            $minutes = $this->minutes;
        }

        return $this->cache->tags($tags)->put($key, $value, $minutes);
    }

    /**
     * Has
     *
     * @param  string $key
     * @param  array $tags
     *
     * @return bool
     */
    public function has($key, array $tags)
    {
        return $this->cache->tags($tags)->has($key);
    }
}

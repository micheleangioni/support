<?php

namespace TopGames\Support\Cache;

use \Illuminate\Cache\CacheManager;

class LaravelCache implements CacheInterface {

    /**
     * @var \Illuminate\Cache\CacheManager
     */
    protected $cache;

    /**
     * @var integer
     */
    protected $minutes;

    /**
     * Construct
     *
     * @param \Illuminate\Cache\CacheManager $cache
     * @param integer $minutes
     */
    public function __construct(CacheManager $cache, $minutes = 1) //TODO minutes = 15
    {
        $this->cache = $cache;
        $this->minutes = $minutes;
    }

    /**
     * Get
     *
     * @param string $key
     * @param array $tags
     * @return mixed
     */
    public function get($key, array $tags)
    {
        return $this->cache->tags($tags)->get($key);
    }

    /**
     * Put
     *
     * @param string $key
     * @param mixed $value
     * @param array $tags
     * @param integer $minutes
     * @return mixed
     */
    public function put($key, $value, array $tags, $minutes = null)
    {
        if( is_null($minutes) )
        {
            $minutes = $this->minutes;
        }

        return $this->cache->tags($tags)->put($key, $value, $minutes);
    }

    /**
     * Has
     *
     * @param string $key
     * @param array $tags
     * @return bool
     */
    public function has($key, array $tags)
    {
        return $this->cache->tags($tags)->has($key);
    }

}
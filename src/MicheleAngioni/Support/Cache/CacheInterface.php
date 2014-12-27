<?php

namespace MicheleAngioni\Support\Cache;

interface CacheInterface {

    /**
     * Get
     *
     * @param  string  $key
     * @param  array   $tags
     * @return mixed
     */
    public function get($key, array $tags);

    /**
     * Put
     *
     * @param  string  $key
     * @param  mixed   $value
     * @param  array   $tags
     * @param  int     $minutes
     *
     * @return mixed
     */
    public function put($key, $value, array $tags, $minutes = null);

    /**
     * Has
     *
     * @param  string  $key
     * @param  array   $tags
     *
     * @return bool
     */
    public function has($key, array $tags);

}
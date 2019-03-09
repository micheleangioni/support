<?php

namespace MicheleAngioni\Support\Cache;

interface CacheInterface
{
    /**
     * Get
     *
     * @param string $key
     * @param array $tags
     *
     * @return mixed
     */
    public function get(string $key, array $tags);

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
    public function put(string $key, $value, array $tags, int $seconds = null);

    /**
     * Has
     *
     * @param string $key
     * @param array $tags
     *
     * @return bool
     */
    public function has(string $key, array $tags): bool;
}

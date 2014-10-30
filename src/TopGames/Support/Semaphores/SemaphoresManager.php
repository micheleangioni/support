<?php

namespace TopGames\Support\Semaphores;

use TopGames\Support\Cache\CacheInterface;
use TopGames\Support\Cache\KeyManagerInterface;

class SemaphoresManager {

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * Key manager object.
     *
     * @var KeyManagerInterface
     */
    protected $keyManager;

    /**
     * Minutes the semaphore remains locked before getting automatically unlocked.
     *
     * @var int
     */
    protected $lockingTime = 2;

    /**
     * Name of the class instantiating the SemaphoresManager
     *
     * @var string
     */
    protected $modelClass;

    /**
     * Construct
     *
     * @param  CacheInterface $cache
     * @param  KeyManagerInterface $keyManager
     */
    public function __construct(CacheInterface $cache, KeyManagerInterface $keyManager)
    {
        $this->cache = $cache;
        $this->keyManager = $keyManager;

        $this->modelClass = debug_backtrace()[1]['function'];
    }

    /**
     * Return the locking time.
     *
     * @return int
     */
    public function getLockingTime()
    {
        return $this->lockingTime;
    }

    /**
     * Set the locking time.
     *
     * @param  int  $minutes
     * @return int
     */
    public function setLockingTime($minutes)
    {
        return $this->lockingTime = (int)$minutes;
    }

    /**
     * Return the key of the semaphore connected with input $id and $section.
     *
     * @param  bool|int  $id
     * @param  string    $section
     * @return string
     */
    public function getSemaphoreId($id, $section)
    {
        return $this->keyManager->getKey($id, [], $section, $this->modelClass);
    }

    /**
     * Check if a semaphore is locked or free. Return 1 if locked or 0 if unlocked.
     *
     * @param  int     $key
     * @param  string  $section
     * @return int
     */
    public function checkIfSemaphoreIsLocked($key, $section)
    {
        if(!$this->cache->has($key, ['semaphore', $section])) {
            return 0;
        }

        return $this->cache->get($key, ['semaphore', $section]);
    }

    /**
     * Lock the semaphore with input key and section.
     *
     * @param  int     $key
     * @param  string  $section
     */
    public function lockSemaphore($key, $section)
    {
        $this->cache->put($key, 1, ['semaphore', $section], $this->lockingTime);
    }

    /**
     * Unlock the semaphore with input key and section.
     *
     * @param  int     $key
     * @param  string  $section
     */
    public function unlockSemaphore($key, $section)
    {
        $this->cache->put($key, 0, ['semaphore', $section], $this->lockingTime);
    }

}
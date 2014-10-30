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
     * Construct
     *
     * @param  CacheInterface $cache
     * @param  KeyManagerInterface $keyManager
     */
    public function __construct(CacheInterface $cache, KeyManagerInterface $keyManager)
    {
        $this->cache = $cache;
        $this->keyManager = $keyManager;
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
    public function getSemaphoreKey($id, $section)
    {
        return $this->keyManager->getKey($id, [], $section);
    }

    /**
     * Check if a semaphore is locked or free. Return 1 if locked or 0 if unlocked.
     *
     * @param  int     $id
     * @param  string  $section
     * @return int
     */
    public function checkIfSemaphoreIsLocked($id, $section)
    {
        $key = $this->getSemaphoreKey($id, $section);

        if(!$this->cache->has($key, ['semaphore', $section])) {
            return 0;
        }

        return (int)$this->cache->get($key, ['semaphore', $section]);
    }

    /**
     * Lock the semaphore with input key and section.
     *
     * @param  int     $id
     * @param  string  $section
     */
    public function lockSemaphore($id, $section)
    {
        $key = $this->getSemaphoreKey($id, $section);

        $this->cache->put($key, 1, ['semaphore', $section], $this->lockingTime);
    }

    /**
     * Unlock the semaphore with input key and section.
     *
     * @param  int     $id
     * @param  string  $section
     */
    public function unlockSemaphore($id, $section)
    {
        $key = $this->getSemaphoreKey($id, $section);

        $this->cache->put($key, 0, ['semaphore', $section], $this->lockingTime);
    }

}
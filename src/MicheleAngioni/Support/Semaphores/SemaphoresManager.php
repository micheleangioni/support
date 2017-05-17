<?php

namespace MicheleAngioni\Support\Semaphores;

use MicheleAngioni\Support\Cache\CacheInterface;
use MicheleAngioni\Support\Cache\KeyManagerInterface;

class SemaphoresManager
{
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
    public function getLockingTime(): int
    {
        return $this->lockingTime;
    }

    /**
     * Set the locking time.
     *
     * @param  int $minutes
     *
     * @return int
     */
    public function setLockingTime(int $minutes): int
    {
        return $this->lockingTime = $minutes;
    }

    /**
     * Return the key of the semaphore connected with input $id and $section.
     *
     * @param  string|bool $id
     * @param  string $section
     *
     * @return string
     */
    public function getSemaphoreKey($id, string $section)
    {
        return $this->keyManager->getKey($id, [], $section);
    }

    /**
     * Check if a semaphore is locked or free. Return 1 if locked or 0 if unlocked.
     *
     * @param  string $id
     * @param  string $section
     *
     * @return int
     */
    public function checkIfSemaphoreIsLocked($id, string $section): int
    {
        $key = $this->getSemaphoreKey($id, $section);

        if (!$this->cache->has($key, ['semaphore', $section])) {
            return 0;
        }

        return (int)$this->cache->get($key, ['semaphore', $section]);
    }

    /**
     * Lock the semaphore with input key and section.
     *
     * @param  string $id
     * @param  string $section
     */
    public function lockSemaphore($id, string $section)
    {
        $key = $this->getSemaphoreKey($id, $section);

        $this->cache->put($key, 1, ['semaphore', $section], $this->lockingTime);
    }

    /**
     * Unlock the semaphore with input key and section.
     *
     * @param  string $id
     * @param  string $section
     */
    public function unlockSemaphore($id, string $section)
    {
        $key = $this->getSemaphoreKey($id, $section);

        $this->cache->put($key, 0, ['semaphore', $section], $this->lockingTime);
    }
}

<?php

namespace MicheleAngioni\Support;

use MicheleAngioni\Support\Semaphores\SemaphoresManager;
use PHPUnit\Framework\TestCase;
use Mockery;

class SemaphoreTest extends TestCase
{

    public function testLockingTime()
    {
        $lockingTime = 12;

        $cacheInterface = $this->mock('MicheleAngioni\Support\Cache\CacheInterface');
        $keyManagerInterface = $this->mock('MicheleAngioni\Support\Cache\KeyManagerInterface');

        $semaphoreManager = new SemaphoresManager($cacheInterface,
            $keyManagerInterface);

        $semaphoreManager->setLockingTime($lockingTime);

        $this->assertEquals($lockingTime, $semaphoreManager->getLockingTime());
    }

    public function testGetSemaphoreKey()
    {
        $cacheInterface = $this->mock('MicheleAngioni\Support\Cache\CacheInterface');
        $keyManagerInterface = $this->mock('MicheleAngioni\Support\Cache\KeyManagerInterface');

        $keyManagerInterface->shouldReceive('getKey')
            ->andReturn(true);

        $semaphoreManager = new SemaphoresManager($cacheInterface,
            $keyManagerInterface);

        $semaphoreManager->getSemaphoreKey('id', 'section');
    }

    public function testLockSemaphore()
    {
        $cacheInterface = $this->mock('MicheleAngioni\Support\Cache\CacheInterface');
        $keyManagerInterface = $this->mock('MicheleAngioni\Support\Cache\KeyManagerInterface');

        $semaphoreManager = Mockery::mock('MicheleAngioni\Support\Semaphores\SemaphoresManager[getSemaphoreKey]',
            [$cacheInterface, $keyManagerInterface]);

        $semaphoreManager->shouldReceive('getSemaphoreKey')
            ->andReturn('string');

        $cacheInterface->shouldReceive('put')
            ->andReturn(true);

        $semaphoreManager->lockSemaphore('id', 'section');
    }

    public function testUnlockSemaphore()
    {
        $cacheInterface = $this->mock('MicheleAngioni\Support\Cache\CacheInterface');
        $keyManagerInterface = $this->mock('MicheleAngioni\Support\Cache\KeyManagerInterface');

        $semaphoreManager = Mockery::mock('MicheleAngioni\Support\Semaphores\SemaphoresManager[getSemaphoreKey]',
            [$cacheInterface, $keyManagerInterface]);

        $semaphoreManager->shouldReceive('getSemaphoreKey')
            ->andReturn('string');

        $cacheInterface->shouldReceive('put')
            ->andReturn(true);

        $semaphoreManager->unlockSemaphore('id', 'section');
    }


    public function mock($class)
    {
        $mock = Mockery::mock($class);

        return $mock;
    }

    public function tearDown()
    {
        Mockery::close();
    }
}

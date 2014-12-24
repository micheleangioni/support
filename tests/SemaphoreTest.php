<?php

class SemaphoreTest extends TestCase {

	public function testLockingTime()
	{
        $lockingTime = 12;

        $cacheInterface = $this->mock('TopGames\Support\Cache\CacheInterface');
        $keyManagerInterface = $this->mock('TopGames\Support\Cache\KeyManagerInterface');

        $semaphoreManager = new TopGames\Support\Semaphores\SemaphoresManager($cacheInterface, $keyManagerInterface);

        $semaphoreManager->setLockingTime($lockingTime);

        $this->assertEquals($lockingTime, $semaphoreManager->getLockingTime());
    }

    public function testGetSemaphoreKey()
    {
        $cacheInterface = $this->mock('TopGames\Support\Cache\CacheInterface');
        $keyManagerInterface = $this->mock('TopGames\Support\Cache\KeyManagerInterface');

        $keyManagerInterface->shouldReceive('getKey')
            ->andReturn(true);

        $semaphoreManager = new TopGames\Support\Semaphores\SemaphoresManager($cacheInterface, $keyManagerInterface);

        $semaphoreManager->getSemaphoreKey('id', 'section');
    }

    public function testLockSemaphore()
    {
        $cacheInterface = $this->mock('TopGames\Support\Cache\CacheInterface');
        $keyManagerInterface = $this->mock('TopGames\Support\Cache\KeyManagerInterface');

        $semaphoreManager = Mockery::mock('TopGames\Support\Semaphores\SemaphoresManager[getSemaphoreKey]', array($cacheInterface, $keyManagerInterface));

        $semaphoreManager->shouldReceive('getSemaphoreKey')
                         ->andReturn('string');

        $cacheInterface->shouldReceive('put')
                       ->andReturn(true);

        $semaphoreManager->lockSemaphore('id', 'section');
    }

    public function testUnlockSemaphore()
    {
        $cacheInterface = $this->mock('TopGames\Support\Cache\CacheInterface');
        $keyManagerInterface = $this->mock('TopGames\Support\Cache\KeyManagerInterface');

        $semaphoreManager = Mockery::mock('TopGames\Support\Semaphores\SemaphoresManager[getSemaphoreKey]', array($cacheInterface, $keyManagerInterface));

        $semaphoreManager->shouldReceive('getSemaphoreKey')
            ->andReturn('string');

        $cacheInterface->shouldReceive('put')
            ->andReturn(true);

        $semaphoreManager->unlockSemaphore('id', 'section');
    }

    public function testCacheIntegration()
    {
        $id = 10;
        $section = 'section';

        $cache = App::make('TopGames\Support\Cache\LaravelCache');
        $keyManager = App::make('TopGames\Support\Cache\KeyManager');

        $semaphoreManager = new TopGames\Support\Semaphores\SemaphoresManager($cache, $keyManager);

        $semaphoreManager->lockSemaphore($id, $section);
        $this->assertEquals(1, $semaphoreManager->checkIfSemaphoreIsLocked($id, $section));

        $semaphoreManager->unlockSemaphore($id, $section);
        $this->assertEquals(0, $semaphoreManager->checkIfSemaphoreIsLocked($id, $section));
    }


    public function mock($class)
    {
        $mock = Mockery::mock($class);

        $this->app->instance($class, $mock);

        return $mock;
    }

    public function tearDown()
    {
        Mockery::close();
    }

}
<?php

namespace TopGames\Support\Cache;

use TopGames\Support\Repos\RepositoryCacheableQueriesInterface;
use BadMethodCallException;

abstract class AbstractCacheRepositoryDecorator implements RepositoryCacheableQueriesInterface {

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
     * Name of current active class
     *
     * @var string
     */
    protected $modelClass;

    /**
     * @var RepositoryCacheableQueriesInterface
     */
    protected $repo;

    /**
     * Section of the Cache the repo belongs to
     *
     * @var string
     */
    protected $section;

    /**
     * Construct
     *
     * @param  RepositoryCacheableQueriesInterface $repo
     * @param  CacheInterface $cache
     * @param  KeyManagerInterface $keyManager
     */
    public function __construct(RepositoryCacheableQueriesInterface $repo, CacheInterface $cache, KeyManagerInterface $keyManager)
    {
        $this->repo = $repo;
        $this->cache = $cache;
        $this->keyManager = $keyManager;

        $reflection = new \ReflectionClass($repo);
        $constructor = $reflection->getConstructor();
        $this->modelClass = $constructor->getParameters()[0]->getClass()->name;
    }

    public function __call($method, $parameters)
    {
        if(method_exists($this, $method))
        {
            return call_user_func_array(array($this, $method), $parameters);
        }

        if(method_exists($this->repo, $method))
        {
            return call_user_func_array(array($this->repo, $method), $parameters);
        }

        throw new BadMethodCallException('RuntimeException in '.__METHOD__.' at line '.__LINE__.": Called not existent method $method by class ".get_class($this));
    }

    /**
     * All
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        $key = $this->getKey();

        $tags = $this->getTags();

        if($this->cache->has($key, $tags))
        {
            return $this->cache->get($key, $tags);
        }

        $collection = $this->repo->all();

        $this->cache->put($key, $collection, $tags);

        return $collection;
    }

    /**
     * Find
     *
     * @param  string  $id
     * @param  array   $with
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id, array $with = array())
    {
        $key = $this->getKey($id, $with);

        $tags = $this->getTags($id, $with);

        if($this->cache->has($key, $tags))
        {
            return $this->cache->get($key, $tags);
        }

        $model = $this->repo->find($id, $with);

        $this->cache->put($key, $model, $tags);

        return $model;
    }

    /**
     * Find or throws exception
     *
     * @param  string  $id
     * @param  array   $with
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrFail($id, array $with = array())
    {
        $key = $this->getKey($id, $with);

        $tags = $this->getTags($id, $with);

        if($this->cache->has($key, $tags))
        {
            return $this->cache->get($key, $tags);
        }

        $model = $this->repo->findOrFail($id, $with);

        $this->cache->put($key, $model, $tags);

        return $model;
    }

    /**
     * Call the Key Manager to get the Cache key.
     *
     * @param  string|bool   $id
     * @param  array         $array
     *
     * @return string
     */
    function getKey($id = false, array $array = array())
    {
        return $this->keyManager->getKey($id, $array, $this->section, $this->modelClass);
    }

    /**
     * Call the Key Manager to get the Cache tags.
     *
     * @param  string|bool  $id
     * @param  array        $array
     *
     * @return array
     */
    function getTags($id = false, array $array = array())
    {
        return $this->keyManager->getTags($id, $array, $this->section, $this->modelClass);
    }

    /**
     * Call the Key Manager method to the Custom Method Key.
     *
     * @param  string|bool  $customName
     * @param  string|bool  $id
     * @param  array        $array
     *
     * @return string
     */
    function getCustomMethodKey($customName = false, $id = false, array $array = array())
    {
        return $this->keyManager->getCustomMethodKey($customName, $id, $array, $this->section, $this->modelClass);
    }

    /**
     * Call the Key Manager method to get the Custom Method Tags.
     *
     * @param  string|bool  $customName
     * @param  string|bool  $id
     * @param  array        $array
     *
     * @return array
     */
    function getCustomMethodTags($customName = false, $id = false, array $array = array())
    {
        return $this->keyManager->getCustomMethodTags($customName, $id, $array, $this->section, $this->modelClass);
    }

}
<?php

namespace TopGames\Support\Cache;

use TopGames\Support\Repos\XMLRepositoryInterface;
use BadMethodCallException;

abstract class AbstractCacheSimpleXMLRepositoryDecorator {

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * Caching minutes.
     *
     * @var int
     */
    protected $minutes = 120;

    /**
     * Section of the Cache the repo belongs to.
     *
     * @var string
     */
    protected $section;

    /**
     * Patch of the xml repo file
     *
     * @var string
     */
    protected $xmlFilePath;

    /**
     * @var XMLRepositoryInterface
     */
    protected $xmlRepo;


    /**
     * Construct
     *
     * @param XMLRepositoryInterface $repo
     * @param CacheInterface $cache
     */
    public function __construct(XMLRepositoryInterface $repo, CacheInterface $cache)
    {
        $this->xmlRepo = $repo;
        $this->cache = $cache;

        $this->xmlFilePath = $this->xmlRepo->getFilePath();
    }


    public function __call($method, $parameters)
    {
        if(method_exists($this, $method))
        {
            return call_user_func_array(array($this, $method), $parameters);
        }

        if(method_exists($this->xmlRepo, $method))
        {
            return call_user_func_array(array($this->xmlRepo, $method), $parameters);
        }

        throw new BadMethodCallException('RuntimeException in '.__METHOD__.' at line '.__LINE__.': Called method does NOT exist.');
    }

    /**
     * Return the xml file as an instance of SimpleXML from the Cache.
     * If the file is not present in the Cache, call loadFile().
     *
     * @return \SimpleXMLElement
     */
    public function getFile()
    {
        $key = $this->getKey();

        $tags = $this->getTags();

        if( !$this->cache->has($key, $tags) )
        {
            $this->loadFile();
        }

        return simplexml_load_string($this->cache->get($key, $tags));
    }

    /**
     * If not already loaded, load the file into the Cache.
     * Since SimpleXML objects (as many others) can't be serialized, loaded xml is formatted as XML before putting in the Cache.
     */
    public function loadFile()
    {
        $key = $this->getKey();

        $tags = $this->getTags();

        if( !$this->cache->has($key, $tags) )
        {
            $this->xmlRepo->loadFile();

            $XMLFile = $this->xmlRepo->getFile()->asXML();

            $this->cache->put($key, $XMLFile, $tags, $this->minutes);
        }
    }

    /**
     * Return Cache key generated by using the current active class, id and $with array
     *
     * @return string
     */
    protected function getKey()
    {
        $string = $this->getString();

        return md5($string);
    }

    /**
     * Return the ready-to-be-encrypted-string key generated by using the current active class, id and $with array
     *
     * @return string
     */
    protected function getString()
    {
        $string = $this->section.$this->xmlFilePath;

        return $string;
    }

    /**
     * Return Cache tags generated by using the current active class, id and with array
     *
     * @return array
     */
    protected function getTags()
    {
        $tags = [$this->section, $this->xmlFilePath];

        return $tags;
    }

    /**
     * Return a method-customized Cache key generated by using the current active class, id and $with array.
     * If not customName is provided, the name of the calling method will be used.
     *
     * @param  string|bool  $customName
     * @return string
     */
    public function getCustomMethodKey($customName = false)
    {
        if( !$customName ) {
            $customName=debug_backtrace()[1]['function'];
        }

        $string = $this->getString().$this->xmlFilePath.$customName;

        return md5($string);
    }

    /**
     * Return Cache tags generated by using the current active class, id and with array.
     * It has an additional method-customized tag.
     * If not customName is provided, the name of the calling method will be used.
     *
     * @param  string|bool  $customName
     * @return array
     */
    public function getCustomMethodTags($customName = false)
    {
        if( !$customName ) {
            $customName=debug_backtrace()[1]['function'];
        }

        $tags = $this->getTags();
        $tags[] = $this->xmlFilePath.$customName;

        return $tags;
    }

}
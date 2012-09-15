<?php

namespace Tickit\CacheBundle\Cache;

use Tickit\CacheBundle\Engine\AbstractEngine;

/**
 * Base cache file that integrates an engine and an options object
 * to provider read/write functionality
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class Cache
{
    /* @var \Tickit\CacheBundle\Engine\AbstractEngine $engine */
    protected $engine;

    /**
     * Class constructor, sets up engine and options objects
     *
     * @param \Tickit\CacheBundle\Engine\AbstractEngine $engine The engine adapter
     */
    public function __construct(AbstractEngine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * Reads data from the cache engine
     *
     * @param string|int $id
     *
     * @return mixed
     */
    public function read($id)
    {
        return $this->getEngine()->internalRead($id);
    }

    /**
     * Writes a bunch of data to the cache engine
     *
     * @param string|int $id   The unique ID for this piece of data
     * @param mixed      $data The data to write to the cache
     *
     * @return mixed
     */
    public function write($id, $data)
    {
        return $this->getEngine()->internalWrite($id, $data);
    }

    /**
     * Gets the caching engine associated with this cache
     *
     * @return \Tickit\CacheBundle\Engine\AbstractEngine
     */
    public function getEngine()
    {
        return $this->engine;
    }
}
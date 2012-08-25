<?php

namespace Tickit\CacheBundle\Engine;

/**
 * Abstract caching engine providing base functionality for data caching
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractEngine
{
    /**
     * Writes data to the cache
     *
     * @param string|int $id   The unique identifier for the cached data
     * @param mixed      $data Either an object, array or string of data to be cached
     *
     * @abstract
     *
     * @return void
     */
    abstract public function write($id, $data);

    /**
     * Reads data from the cache and returns it in its pre-cached state
     *
     * @param int $id The unique identifier of the data to read
     *
     * @abstract
     *
     * @return mixed
     */
    abstract public function read($id);
}
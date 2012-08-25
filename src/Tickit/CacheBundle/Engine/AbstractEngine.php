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
     * @var bool $tags Boolean value indicating whether the caching engine supports tags
     */
    protected $tags = false;

    /**
     * Writes data to the cache
     *
     * @param string|int $id   The unique identifier for the cached data
     * @param mixed      $data Either an object, array or string of data to be cached
     * @param array      $tags [Optional] Tags to be associated with the cached data (on supported engines)
     *
     * @abstract
     *
     * @return void
     */
    abstract public function write($id, $data, array $tags = null);

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
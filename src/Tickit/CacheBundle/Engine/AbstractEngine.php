<?php

namespace Tickit\CacheBundle\Engine;

use Tickit\CacheBundle\Options\AbstractOptions;

/**
 * Abstract caching engine providing base functionality for data caching
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractEngine
{
    /* @var \Tickit\CacheBundle\Options\AbstractOptions $options */
    protected $options;

    /**
     * Writes data to the cache, triggers the {@see internalWrite()} method internally
     *
     * @param string|int $id   The unique identifier for the cached data
     * @param mixed      $data Either an object, array or string of data to be cached
     *
     * @return void
     */
    public function write($id, $data)
    {
        $this->internalWrite($id, $data);
    }

    /**
     * Internal method that provides adapter specific cache writing logic
     *
     * @param string|int $id   The unique identifier of the data to cache
     * @param mixed      $data The actual data to cache
     *
     * @abstract
     *
     * @return void
     */
    abstract protected function internalWrite($id, $data);

    /**
     * Reads data from the cache and returns it in its pre-cached state, triggers the {@see internalRead()} method
     * internally
     *
     * @param int $id The unique identifier of the data to read
     *
     * @return mixed
     */
    public function read($id)
    {
        return $this->internalRead($id);
    }

    /**
     * Internal method that provides adapter specific cache reading logic
     *
     * @param string|int $id The unique identifier of the data to read
     *
     * @abstract
     *
     * @return mixed
     */
    abstract protected function internalRead($id);

    /**
     * Sets up the options for the cache
     *
     * @param mixed $options Either an instance of AbstractOptions or an array of options for the cache
     *
     * @return \Tickit\CacheBundle\Options\AbstractOptions;
     */
    protected function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * Gets the options object for this cache
     *
     * @return \Tickit\CacheBundle\Options\AbstractOptions
     */
    public function getOptions()
    {
        return $this->options;
    }
}
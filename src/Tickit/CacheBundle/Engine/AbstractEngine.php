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
     * Internal method that provides adapter specific cache writing logic
     *
     * @param string|int $id   The unique identifier of the data to cache
     * @param mixed      $data The actual data to cache
     *
     * @abstract
     *
     * @return mixed
     */
    abstract public function internalWrite($id, $data);

    /**
     * Internal method that provides adapter specific cache reading logic
     *
     * @param string|int $id The unique identifier of the data to read
     *
     * @abstract
     *
     * @return mixed
     */
    abstract public function internalRead($id);

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
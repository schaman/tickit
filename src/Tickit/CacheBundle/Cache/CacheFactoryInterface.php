<?php

namespace Tickit\CacheBundle\Cache;

/**
 * Interface for a class that provides a cache factory
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
interface CacheFactoryInterface
{
    /**
     * @param string $engine  The name of the cache engine to use
     * @param array  $options An array of options used to override the application defaults (in app/config file)
     *
     * @abstract
     *
     * @return \Tickit\CacheBundle\Engine\AbstractEngine
     */
    public function factory($engine, array $options = null);

}
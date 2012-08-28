<?php

namespace Tickit\CacheBundle\Cache;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Core cache file which provides top level access to caching engines.
 *
 * This factory class is available via the service container:
 *
 * <code>
 *      $container->get('tickit_cache.factory');
 * </code>
 *
 * Or if you're in a controller...
 *
 * <code>
 *      $this->get('tickit_cache.factory');
 * </code>
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class Cache implements CacheFactoryInterface
{
    const MEMCACHED_ENGINE = 'memcached';
    const APC_ENGINE = 'apc';
    const FILE_ENGINE = 'file';

    protected $container;

    /**
     * Class constructor, sets dependencies
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container The dependency injection container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Factory method that instantiates a caching engine
     *
     * @param string $engine  The name of the caching engine to instantiate
     * @param array  $options An array of options to override the application defaults
     *
     * @throws \Tickit\CacheBundle\Exception\InvalidArgumentException If an invalid caching engine is provided
     *
     * @return \Tickit\CacheBundle\Engine\AbstractEngine
     */
    public function factory($engine, array $options = null)
    {
        if (false === $this->_isEngineValid($engine)) {
            throw new \Tickit\CacheBundle\Exception\InvalidArgumentException();
        }

        //do any configuration here

        $engineClass = sprintf('\Tickit\CacheBundle\Engine\%sEngine', ucfirst($engine));

        return new $engineClass($this->container);
    }

    /**
     * Verifies that a caching engine name is valid
     *
     * @param string $engine
     *
     * @return bool
     */
    private function _isEngineValid($engine)
    {
        return in_array($engine, array(
            self::MEMCACHED_ENGINE,
            self::APC_ENGINE,
            self::FILE_ENGINE
        ));
    }

}
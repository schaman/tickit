<?php

namespace Tickit\CacheBundle\Engine;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Caching engine for storing data in memcached server instance(s)
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class MemcachedEngine extends AbstractEngine
{

    /* @var \Symfony\Component\DependencyInjection\ContainerInterface */
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
     * {@inheritDoc}
     */
    public function write($id, $data, array $tags = null)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function read($id)
    {
        return '';
    }

}
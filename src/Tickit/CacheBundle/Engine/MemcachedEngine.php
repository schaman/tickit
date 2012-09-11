<?php

namespace Tickit\CacheBundle\Engine;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Tickit\CacheBundle\Options\MemcachedOptions;

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
     * @param array                                                     $options   An array of options for the cache
     */
    public function __construct(ContainerInterface $container, array $options = null)
    {
        $this->container = $container;
        $this->setOptions($options);
    }

    /**
     * {@inheritDoc}
     */
    public function internalWrite($id, $data)
    {
        //write data to memcached cache
    }

    /**
     * {@inheritDoc}
     */
    public function internalRead($id)
    {
        return '';
    }


    /**
     * {@inheritDoc}
     */
    protected function setOptions($options)
    {
        if (!$options instanceof MemcachedOptions) {
            $options = new MemcachedOptions($options, $this->container);
        }

        $this->options = $options;

        return $this->options;
    }

}
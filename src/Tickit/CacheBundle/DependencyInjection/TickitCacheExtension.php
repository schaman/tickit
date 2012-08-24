<?php

namespace Tickit\CacheBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Custom dependency injection extension for the cache bundle
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class TickitCacheExtension extends Extension
{

    /**
     * Loads dependency injection configuration
     *
     * @param array                                                   $configs   An array of configs from app/config.yml
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container Instance of the ContainerBuilder class
     */
    public function load(array $configs, ContainerBuilder $container)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getAlias()
    {
        return 'tickit_cache';
    }
}
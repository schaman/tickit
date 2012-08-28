<?php

namespace Tickit\CacheBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;

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
        $xmlLoader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $xmlLoader->load('services.xml');
    }

    /**
     * {@inheritDoc}
     */
    public function getAlias()
    {
        return 'tickit_cache';
    }
}
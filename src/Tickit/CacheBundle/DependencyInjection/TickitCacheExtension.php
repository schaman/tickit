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
        $configuration = new Configuration();
        $processor = new Processor();

        $config = $processor->processConfiguration($configuration, $configs);

        $xmlLoader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $xmlLoader->load('services.xml');

        if (is_array($config['types']['file'])) {
            $this->loadFile($config['types']['file'], $container);
        }

        if (is_array($config['types']['memcached'])) {
            $this->loadMemcached($config['types']['memcached'], $container);
        }

        $container->setParameter('tickit_cache.default_namespace', $config['default_namespace']);
        $container->setParameter('tickit_cache.apc.enabled', $config['types']['apc']);
    }

    /**
     * Loads configuration for file cache
     *
     * @param array                                                   $config    The array of configs for the file cache
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container The container builder instance
     */
    protected function loadFile($config, ContainerBuilder $container)
    {
        $params = array('default_path', 'auto_serialize', 'umask', 'directory_base');
        foreach ($params as $param) {
            $container->setParameter(sprintf('tickit_cache.file.%s', $param), $config[$param]);
        }
    }

    /**
     * Loads configuration for memcached cache
     *
     * @param array                                                   $config    The array of configs for memcached cache
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container The container builder instance
     */
    protected function loadMemcached($config, ContainerBuilder $container)
    {
        return; //todo -- add this to configuration
    }

    /**
     * {@inheritDoc}
     */
    public function getAlias()
    {
        return 'tickit_cache';
    }
}
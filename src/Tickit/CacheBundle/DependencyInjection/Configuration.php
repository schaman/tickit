<?php

namespace Tickit\CacheBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Sets up and processes configuration information for the cache bundle
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class Configuration implements ConfigurationInterface
{

    /**
     * Generates a configuration tree for the bundle
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $builder->root('tickit_cache')
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('types')
                    ->children()
                        ->arrayNode('file')->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('default_path')->defaultValue('%kernel.root_dir%/cache')->end()
                                ->booleanNode('auto_serialize')->defaultValue(false)->end()
                                ->scalarNode('default_prefix')->defaultValue('tickit_cache')->end()
                                ->scalarNode('umask')->defaultValue('600')->end()
                            ->end()
                        ->end()
                        ->arrayNode('memcached')->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('servers')
                                    ->prototype('array')
                                        ->children()
                                            ->scalarNode('host')->end()
                                            ->scalarNode('port')->end()
                                            ->booleanNode('persistent')->defaultValue(true)->end()
                                            ->scalarNode('weight')->defaultValue(1)->end()
                                            ->scalarNode('retry_time')->defaultValue(10)->end()
                                            ->booleanNode('enabled')->defaultValue(true)->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->booleanNode('apc')->defaultValue(false)->end()
                    ->end()
                ->end()
            ->end();

        return $builder;
    }
}
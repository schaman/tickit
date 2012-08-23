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
            ->children()
                ->arrayNode('types')
                    ->children()
                        ->arrayNode('file')->defaultValue(false)
                            ->children()
                                ->scalarNode('default_path')->defaultValue('%root%/app/cache')->end()
                                ->booleanNode('auto_serialize')->defaultValue(false)->end()
                                ->scalarNode('prefix')->defaultValue('tickit_cache')->end()
                            ->end()
                        ->end()
                        ->arrayNode('memcache')
                            ->children()
                                ->arrayNode('servers')
                                //TODO: how do we define potentially infinite servers here?
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $builder;
    }
}
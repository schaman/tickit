<?php

namespace Tickit\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * User bundle configuration
 *
 * @package Tickit\UserBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $builder->root('tickit_user')
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('avatar')
                    ->children()
                        ->scalarNode('adapter_class')->end()
                    ->end()
                ->end()
            ->end();

        return $builder;
    }
}
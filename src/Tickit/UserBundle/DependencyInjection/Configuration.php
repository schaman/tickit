<?php

namespace Tickit\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * User bundle configuration
 *
 * @package Tickit\UserBundle\DependencyInjection
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     *
     * @return TreeBuilder
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

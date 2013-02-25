<?php

namespace Tickit\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration implements ConfigurationInterface
{
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
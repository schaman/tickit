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

        //do config tree here

        return $builder;
    }
}
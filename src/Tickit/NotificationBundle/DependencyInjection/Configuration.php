<?php

namespace Tickit\NotificationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration class for notification bundle
 *
 *
 * @package Tickit\NotificationBundle\DependencyInjection
 * @author  James Halsall <james.t.halsall@googlemail.com>
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
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('tickit_notification')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('api_message_limit')->defaultValue(25)->end()
                    ->end();

        return $treeBuilder;
    }
}

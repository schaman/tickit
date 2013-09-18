<?php

namespace Tickit\PermissionBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * PermissionBundle DI extension class.
 *
 * @package Tickit\PermissionBundle\DependencyInjection
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TickitPermissionExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @param array            $config    An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @return void
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $xmlLoader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $xmlLoader->load('services.xml');
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getAlias()
    {
        return 'tickit_permission';
    }
}

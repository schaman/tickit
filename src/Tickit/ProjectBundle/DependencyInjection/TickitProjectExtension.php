<?php

namespace Tickit\ProjectBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * Extension class for the project bundle
 *
 * @package Tickit\ProjectBundle\DependencyInjection
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TickitProjectExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @param array            $config    An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     *
     * @api
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $xmlLoader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $xmlLoader->load('services.xml');
        $xmlLoader->load('forms.xml');
        $xmlLoader->load('listeners.xml');
        $xmlLoader->load('managers.xml');
        $xmlLoader->load('repositories.xml');
        $xmlLoader->load('controllers.xml');
    }
}

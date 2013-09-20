<?php

namespace Tickit\ClientBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Client bundle DI extension
 *
 * @package Tickit\ClientBundle\DependencyInjection
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TickitClientExtension extends Extension
{
    /**
     * {@inheritDoc}
     *
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}

<?php

namespace Tickit\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * User bundle DI extension
 *
 * @package Tickit\UserBundle\DependencyInjection
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class TickitUserExtension extends Extension
{
    /**
     * Loads configuration.
     *
     * @param array            $configs   Configuration array
     * @param ContainerBuilder $container Service container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $processor     = new Processor();

        $config = $processor->processConfiguration($configuration, $configs);

        $xmlLoader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $xmlLoader->load('services.xml');

        $container->setParameter('tickit_user.avatar.adapter_class', $config['avatar']['adapter_class']);
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getAlias()
    {
        return 'tickit_user';
    }
}

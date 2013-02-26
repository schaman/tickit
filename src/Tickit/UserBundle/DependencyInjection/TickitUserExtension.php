<?php

namespace Tickit\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * User bundle DI extension
 *
 * @package Tickit\UserBundle\DependencyInjection
 */
class TickitUserExtension extends Extension
{
    /**
     * @param array                                                   $configs   Configuration array
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container Service container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $processor     = new Processor();

        $config = $processor->processConfiguration($configuration, $configs);

        $ymlLoader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $ymlLoader->load('services.yml');

        $container->setParameter('tickit_user.avatar.adapter_class', $config['avatar']['adapter_class']);
    }

    /**
     * {@inheritDoc}
     */
    public function getAlias()
    {
        return 'tickit_user';
    }
}
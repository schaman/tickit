<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tickit\Bundle\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * User bundle DI extension
 *
 * @package Tickit\Bundle\UserBundle\DependencyInjection
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
        $xmlLoader->load('forms.xml');
        $xmlLoader->load('managers.xml');
        $xmlLoader->load('listeners.xml');
        $xmlLoader->load('repositories.xml');
        $xmlLoader->load('controllers.xml');

        $container->setParameter('tickit_user.avatar.adapter_class', $config['avatar']['adapter_class']);
        $container->setParameter('tickit_user.login.background_image_path', $config['login']['background_image_path']);
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

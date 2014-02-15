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

namespace Tickit\Bundle\PreferenceBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Tickit\Component\DependencyInjection\ContainerConfigurationLoader;

/**
 * PreferenceBundle extension.
 *
 * @package Tickit\Bundle\PreferenceBundle\DependencyInjection
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TickitPreferenceExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @param array            $config    An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     *
     * @return void
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $xmlLoader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader = new ContainerConfigurationLoader($xmlLoader);

        $loader->loadServices();
        $loader->loadManagers();
        $loader->loadRepositories();
        $loader->loadControllers();
        $loader->loadListeners();
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getAlias()
    {
        return 'tickit_preference';
    }
}

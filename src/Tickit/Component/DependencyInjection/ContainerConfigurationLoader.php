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

namespace Tickit\Component\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * Container configuration loader.
 *
 * Provides methods for assisting with loading service extensions and helps
 * enforce naming conventions across configuration files.
 *
 * @package Tickit\Component\DependencyInjection
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ContainerConfigurationLoader
{
    /**
     * The XML configuration loader for the container
     *
     * @var XmlFileLoader
     */
    private $loader;

    /**
     * @param XmlFileLoader $loader
     */
    public function __construct(XmlFileLoader $loader)
    {
        $this->loader = $loader;
    }
    
    /**
     * Loads general services.xml configuration
     */
    public function loadServices()
    {
        $this->loader->load('services.xml');
    }

    /**
     * Loads forms.xml configuration
     */
    public function loadForms()
    {
        $this->loader->load('forms.xml');
    }

    /**
     * Loads listeners.xml configuration
     */
    public function loadListeners()
    {
        $this->loader->load('listeners.xml');
    }

    /**
     * Loads managers.xml configuration
     */
    public function loadManagers()
    {
        $this->loader->load('managers.xml');
    }

    /**
     * Loads repositories.xml configuration
     */
    public function loadRepositories()
    {
        $this->loader->load('repositories.xml');
    }

    /**
     * Loads controllers.xml configuration
     */
    public function loadControllers()
    {
        $this->loader->load('controllers.xml');
    }
}

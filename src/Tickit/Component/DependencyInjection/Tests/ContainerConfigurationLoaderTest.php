<?php

/*
 * Tickit, an open source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
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

namespace Tickit\Component\DependencyInjection\Tests;

use Tickit\Component\DependencyInjection\ContainerConfigurationLoader;

/**
 * ContainerConfigurationLoader tests
 *
 * @package Tickit\Component\DependencyInjecftion
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ContainerConfigurationLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $xmlLoader;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->xmlLoader = $this->getMockBuilder('\Symfony\Component\DependencyInjection\Loader\XmlFileLoader')
                                ->disableOriginalConstructor()
                                ->getMock();
    }

    /**
     * Tests the loadServices() method
     */
    public function testLoadServices()
    {
        $this->trainXmlLoaderToLoadConfig('services.xml');

        $this->getLoader()->loadServices();
    }

    /**
     * Tests the loadForms() method
     */
    public function testLoadForms()
    {
        $this->trainXmlLoaderToLoadConfig('forms.xml');

        $this->getLoader()->loadForms();
    }

    /**
     * Tests the loadListeners() method
     */
    public function testLoadListeners()
    {
        $this->trainXmlLoaderToLoadConfig('listeners.xml');

        $this->getLoader()->loadListeners();
    }

    /**
     * Tests the loadManagers() method
     */
    public function testLoadManagers()
    {
        $this->trainXmlLoaderToLoadConfig('managers.xml');

        $this->getLoader()->loadManagers();
    }

    /**
     * Tests the loadRepositories() method
     */
    public function testLoadRepositories()
    {
        $this->trainXmlLoaderToLoadConfig('repositories.xml');

        $this->getLoader()->loadRepositories();
    }

    /**
     * Tests the loadControllers() method
     */
    public function testLoadControllers()
    {
        $this->trainXmlLoaderToLoadConfig('controllers.xml');

        $this->getLoader()->loadControllers();
    }

    /**
     * Gets a new configuration loader instance
     *
     * @return ContainerConfigurationLoader
     */
    private function getLoader()
    {
        return new ContainerConfigurationLoader($this->xmlLoader);
    }

    private function trainXmlLoaderToLoadConfig($fileName)
    {
        $this->xmlLoader->expects($this->once())
                        ->method('load')
                        ->with($fileName);
    }
}

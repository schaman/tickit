<?php

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

        $this->getLoader()->loadServices();
    }

    /**
     * Tests the loadListeners() method
     */
    public function testLoadListeners()
    {
        $this->trainXmlLoaderToLoadConfig('listeners.xml');

        $this->getLoader()->loadServices();
    }

    /**
     * Tests the loadManagers() method
     */
    public function testLoadManagers()
    {
        $this->trainXmlLoaderToLoadConfig('managers.xml');

        $this->getLoader()->loadServices();
    }

    /**
     * Tests the loadRepositories() method
     */
    public function testLoadRepositories()
    {
        $this->trainXmlLoaderToLoadConfig('repositories.xml');

        $this->getLoader()->loadServices();
    }

    /**
     * Tests the loadControllers() method
     */
    public function testLoadControllers()
    {
        $this->trainXmlLoaderToLoadConfig('controllers.xml');

        $this->getLoader()->loadServices();
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

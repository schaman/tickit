<?php

namespace Tickit\ClientBundle\Tests\Converter;

use Tickit\ClientBundle\Converter\ClientIdToStringValueConverter;
use Tickit\ClientBundle\Entity\Client;

/**
 * ClientIdToStringValueConverter tests
 *
 * @package Tickit\ClientBundle\Tests\Converter
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientIdToStringValueConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $manager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $decorator;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->manager = $this->getMockBuilder('\Tickit\ClientBundle\Manager\ClientManager')
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->decorator = $this->getMockBuilder('\Tickit\ClientBundle\Decorator\ClientEntityNameDecorator')
                                ->disableOriginalConstructor()
                                ->getMock();
    }
    
    /**
     * Tests the convert() method
     *
     * @expectedException \Doctrine\ORM\EntityNotFoundException
     */
    public function testConvertThrowsExceptionIfNoClientFound()
    {
        $this->manager->expects($this->once())
                      ->method('find')
                      ->with(1)
                      ->will($this->returnValue(null));

        $this->getConverter()->convert(1);
    }

    /**
     * Tests the convert() method
     */
    public function testConvertReturnsExceptedValue()
    {
        $client = new Client();
        $client->setName('name');

        $this->manager->expects($this->once())
                      ->method('find')
                      ->with(1)
                      ->will($this->returnValue($client));

        $this->decorator->expects($this->once())
                        ->method('decorate')
                        ->with($client)
                        ->will($this->returnValue($client->getName()));

        $this->assertEquals($client->getName(), $this->getConverter()->convert(1));
    }

    /**
     * Gets a new converter instance
     *
     * @return ClientIdToStringValueConverter
     */
    private function getConverter()
    {
        return new ClientIdToStringValueConverter($this->manager, $this->decorator);
    }
}
 
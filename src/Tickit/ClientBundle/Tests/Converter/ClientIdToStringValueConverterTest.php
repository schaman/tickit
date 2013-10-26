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
 
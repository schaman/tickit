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

namespace Tickit\Bundle\ClientBundle\Tests\Form\Type\Picker\DataTransformer;

use Tickit\Component\Model\Client\Client;
use Tickit\Bundle\ClientBundle\Form\Type\Picker\DataTransformer\ClientPickerDataTransformer;
use Tickit\Component\Test\AbstractUnitTest;

/**
 * ClientPickerDataTransformer tests
 *
 * @package Tickit\Bundle\ClientBundle\Tests\Form\Type\Picker\DataTransformer
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientPickerDataTransformerTest extends AbstractUnitTest
{
    /**
     * @var ClientPickerDataTransformer
     */
    private $sut;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $manager;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->manager = $this->getMockBuilder('\Tickit\Component\Entity\Manager\ClientManager')
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->sut = new ClientPickerDataTransformer($this->manager);
    }

    /**
     * Tests the getEntityIdentifier() method
     */
    public function testGetEntityIdentifierReturnsCorrectProperty()
    {
        $method = static::getNonAccessibleMethod(get_class($this->sut), 'getEntityIdentifier');

        $this->assertEquals('id', $method->invoke($this->sut));
    }

    /**
     * Tests the findEntityByIdentifier() method
     */
    public function testFindEntityByIdentifierFindsEntity()
    {
        $method = static::getNonAccessibleMethod(get_class($this->sut), 'findEntityByIdentifier');

        $client = new Client();
        $client->setId(1);

        $this->manager->expects($this->once())
                      ->method('find')
                      ->with(1)
                      ->will($this->returnValue($client));

        $this->assertEquals($client, $method->invokeArgs($this->sut, [1]));
    }
}

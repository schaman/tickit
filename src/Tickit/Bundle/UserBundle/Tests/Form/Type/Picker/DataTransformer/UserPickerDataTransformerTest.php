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

namespace Tickit\Bundle\UserBundle\Tests\Form\Type\Picker\DataTransformer;

use Tickit\Component\Test\AbstractUnitTest;
use Tickit\Component\Model\User\User;
use Tickit\Bundle\UserBundle\Form\Type\Picker\DataTransformer\UserPickerDataTransformer;

/**
 * UserPickerDataTransformer tests
 *
 * @package Tickit\Bundle\UserBundle\Tests\Form\Type\Picker\DataTransformer
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserPickerDataTransformerTest extends AbstractUnitTest
{
    /**
     * @var UserPickerDataTransformer
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
        $this->manager = $this->getMockBuilder('\Tickit\Component\Entity\Manager\UserManager')
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->sut = new UserPickerDataTransformer($this->manager);
    }

    /**
     * Tests the transformEntityToSimpleObject() method
     */
    public function testTransformEntityToSimpleObjectReturnsExpectedObject()
    {
        $user = new User();
        $user->setId(1)
             ->setForename('James')
             ->setSurname('Halsall');

        $method = static::getNonAccessibleMethod(get_class($this->sut), 'transformEntityToSimpleObject');

        $simpleObject = $method->invokeArgs($this->sut, [$user]);

        $this->assertInstanceOf('\stdClass', $simpleObject);
        $this->assertEquals(1, $simpleObject->id);
        $this->assertEquals('James Halsall', $simpleObject->text);
    }

    /**
     * Tests the findEntityByIdentifier() method
     */
    public function testFindEntityByIdentifierFindsEntity()
    {
        $method = static::getNonAccessibleMethod(get_class($this->sut), 'findEntityByIdentifier');

        $user = new User();
        $user->setId(1);

        $this->manager->expects($this->once())
                      ->method('find')
                      ->with(1)
                      ->will($this->returnValue($user));

        $this->assertEquals($user, $method->invokeArgs($this->sut, [1]));
    }
}

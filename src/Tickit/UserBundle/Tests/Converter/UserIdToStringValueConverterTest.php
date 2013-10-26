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

namespace Tickit\UserBundle\Tests\Converter;

use Tickit\UserBundle\Converter\UserIdToStringValueConverter;
use Tickit\UserBundle\Entity\User;

/**
 * User converter tests
 *
 * @package Tickit\UserBundle\Tests\Converter
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class UserIdToStringValueConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $userManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $decorator;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->userManager = $this->getMockBuilder('\Tickit\UserBundle\Manager\UserManager')
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $this->decorator = $this->getMockBuilder('\Tickit\UserBundle\Decorator\UserEntityDisplayNameDecorator')
                                ->disableArgumentCloning()
                                ->getMock();
    }

    /**
     * Tests convert() method
     *
     * Checks the display name output from user Id input
     *
     * @return void
     */
    public function testConvertDisplayNameOutput()
    {
        $user = new User();
        $user->setForename('Joe')->setSurname('Bloggs');

        $this->userManager->expects($this->once())
                          ->method('find')
                          ->with(123)
                          ->will($this->returnValue($user));

        $this->decorator->expects($this->once())
                        ->method('decorate')
                        ->with($user)
                        ->will($this->returnValue($user->getFullName()));

        $displayName = $this->getConverter()->convert(123);

        $this->assertEquals($user->getFullName(), $displayName);
    }

    /**
     * Tests the convert() method
     *
     * @expectedException \Doctrine\ORM\EntityNotFoundException
     */
    public function testConvertThrowsExceptionWhenUserNotFound()
    {
        $this->userManager->expects($this->once())
                          ->method('find')
                          ->with(1)
                          ->will($this->returnValue(null));

        $this->getConverter()->convert(1);
    }

    /**
     * Gets a converter instance
     *
     * @return UserIdToStringValueConverter
     */
    private function getConverter()
    {
        return new UserIdToStringValueConverter($this->userManager, $this->decorator);
    }
}

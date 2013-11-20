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

namespace Tickit\Component\Security\Tests;

use Tickit\Component\Model\User\User;
use Tickit\Component\Security\UserProvider;
use Tickit\Component\Security\Tests\Mock\MockInvalidTypeUser;

/**
 * UserProvider tests
 *
 * @package Tickit\Component\Security\Tests
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $userManager;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->userManager = $this->getMockBuilder('\Tickit\Component\Entity\Manager\UserManager')
                                  ->disableOriginalConstructor()
                                  ->getMock();
    }

    /**
     * Tests the supportsClass() method
     *
     * @return void
     */
    public function testSupportsClassReturnsTrueForValidClass()
    {
        $this->userManager->expects($this->once())
                          ->method('getClass')
                          ->will($this->returnValue('Tickit\Component\Model\User\User'));

        $this->assertTrue($this->getProvider()->supportsClass('Tickit\Component\Model\User\User'));
    }

    /**
     * Tests the supportsClass() method
     *
     * @return void
     */
    public function testSupportsClassReturnsFalseForInvalidClass()
    {
        $this->userManager->expects($this->once())
                          ->method('getClass')
                          ->will($this->returnValue('Tickit\Component\Model\User\User'));

        $this->assertFalse($this->getProvider()->supportsClass('\stdClass'));
    }

    /**
     * Tests the loadByUsername() method
     *
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testLoadByUsernameThrowsExceptionForNonExistentUser()
    {
        $user = new User();
        $user->setUsername('username');

        $this->userManager->expects($this->once())
                          ->method('findUserByUsernameOrEmail')
                          ->with($user->getUsername())
                          ->will($this->returnValue(null));

        $this->getProvider()->loadUserByUsername($user->getUsername());
    }

    /**
     * Tests the loadByUsername() method
     */
    public function testLoadByUsernameReturnsExceptedUser()
    {
        $user = new User();
        $user->setUsername('username');

        $this->userManager->expects($this->once())
                          ->method('findUserByUsernameOrEmail')
                          ->with($user->getUsername())
                          ->will($this->returnValue($user));

        $this->assertSame($user, $this->getProvider()->loadUserByUsername($user->getUsername()));
    }

    /**
     * Tests the refreshUser() method
     *
     * @expectedException \Symfony\Component\Security\Core\Exception\UnsupportedUserException
     */
    public function testRefreshUserThrowsExceptionForInvalidUserInstance()
    {
        $this->getProvider()->refreshUser(new MockInvalidTypeUser());
    }

    /**
     * Tests the refreshUser() method
     *
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testRefreshUserThrowsExceptionForNonExistentUser()
    {
        $user = new User();

        $provider = $this->getProvider();
        $provider->refreshUser($user);
    }

    /**
     * Tests the refreshUser() method
     */
    public function testRefreshUserReturnsReloadedUser()
    {
        $user = new User();
        $user->setId(1);
        $reloadedUser = new User();

        $this->userManager->expects($this->once())
                          ->method('find')
                          ->with($user->getId())
                          ->will($this->returnValue($reloadedUser));

        $this->assertSame($reloadedUser, $this->getProvider()->refreshUser($user));
    }

    /**
     * Gets a user provider
     *
     * @return UserProvider
     */
    private function getProvider()
    {
        return new UserProvider($this->userManager);
    }
}

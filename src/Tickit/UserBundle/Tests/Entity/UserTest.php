<?php

/*
 * 
 * Tickit, an source web based bug management tool.
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
 * 
 */

namespace Tickit\UserBundle\Tests\Entity;

use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Entity\UserSession;

/**
 * User entity tests.
 *
 * @package Tickit\UserBundle\Tests\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the getFullName() method
     *
     * @return void
     */
    public function testGetFullNameReturnsCorrectValue()
    {
        $user = new User();
        $user->setForename('forename')
             ->setSurname('surname');

        $this->assertEquals('forename surname', $user->getFullName());
    }

    /**
     * Tests the addSession() method
     */
    public function testAddSessionAddsToCollection()
    {
        $user = new User();
        $this->assertEmpty($user->getSessions()->toArray());

        $user->addSession(new UserSession());
        $this->assertEquals(1, $user->getSessions()->count());
    }

    /**
     * Tests the setAdmin() method
     *
     * @return void
     */
    public function testSetAdminGrantsAdminRoleWhenPassingTrue()
    {
        $user = new User();
        $user->setAdmin(true);

        $this->assertTrue($user->isAdmin());
    }

    /**
     * Tests the setAdmin() method
     *
     * @return void
     */
    public function testSetAdminRemovesAdminRoleWhenPassingFalse()
    {
        $user = new User();
        $user->setAdmin(true);

        $this->assertTrue($user->isAdmin());
        $user->setAdmin(false);
        $this->assertFalse($user->isAdmin());
    }
}

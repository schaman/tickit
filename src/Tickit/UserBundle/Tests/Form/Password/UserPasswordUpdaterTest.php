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

namespace Tickit\UserBundle\Tests\Form\Password;

use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Form\Password\UserPasswordUpdater;

/**
 * UserPasswordUpdater tests
 *
 * @package Tickit\UserBundle\Tests\Form\Password
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserPasswordUpdaterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserPasswordUpdater
     */
    private $passwordUpdater;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->passwordUpdater = new UserPasswordUpdater();
    }

    /**
     * Tests the updatePassword() method
     */
    public function testUpdatePasswordKeepsOriginalPasswordWhenUpdatedUserHasNullPassword()
    {
        $newUser = new User();
        $newUser->setPassword(null);

        $originalUser = new User();
        $originalUser->setPassword('encoded-password');

        $newUser = $this->passwordUpdater->updatePassword($originalUser, $newUser);

        $this->assertEquals('encoded-password', $newUser->getPassword());
    }

    /**
     * Tests the updatePassword() method
     */
    public function testUpdatePasswordSetsNewPassword()
    {
        $newUser = new User();
        $newUser->setPassword('encoded-password');

        $originalUser = new User();

        $newUser = $this->passwordUpdater->updatePassword($originalUser, $newUser);

        $this->assertEquals('encoded-password', $newUser->getPlainPassword());
    }
}

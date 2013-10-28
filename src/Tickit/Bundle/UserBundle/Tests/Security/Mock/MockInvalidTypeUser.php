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

namespace Tickit\Bundle\UserBundle\Tests\Security\Mock;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Mock invalid user type that implements UserInterface
 *
 * @package Tickit\Bundle\UserBundle\Tests\Security\Mock
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class MockInvalidTypeUser implements UserInterface
{
    /**
     * Returns the roles granted to the user.
     *
     * @return void
     */
    public function getRoles()
    {
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * @return void
     */
    public function getPassword()
    {
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return void
     */
    public function getSalt()
    {
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     *
     * @return void
     */
    public function eraseCredentials()
    {
    }
}

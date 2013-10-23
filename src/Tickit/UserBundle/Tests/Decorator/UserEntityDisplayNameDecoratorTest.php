<?php

/*
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
 */

namespace Tickit\UserBundle\Tests\Decorator;

use Tickit\UserBundle\Decorator\UserEntityDisplayNameDecorator;
use Tickit\UserBundle\Entity\User;

/**
 * User entity display name decorator tests
 *
 * @package Tickit\UserBundle\Tests\Decorator
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class UserEntityDisplayNameDecoratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests decorate() method on UserEntityDisplayNameDecorator
     */
    public function testDecoratorUserOutput()
    {
        $user = new User();
        $user->setForename('Joe')->setSurname('Bloggs');

        $decorator = new UserEntityDisplayNameDecorator();

        $this->assertEquals('Joe Bloggs', $decorator->decorate($user));
    }
}

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

namespace Tickit\Component\Security\Tests\Role\Decorator;

use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Tickit\Component\Security\Role\Decorator\RoleDecorator;

/**
 * RoleDecorator tests
 *
 * @package Tickit\Component\Security\Tests\Role\Decorator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class RoleDecoratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the decorate() method
     *
     * @expectedException \OutOfBoundsException
     */
    public function testDecorateThrowsExceptionForInvalidRoleName()
    {
        $this->getDecorator()->decorate(new Role('invalid role'));
    }

    /**
     * Tests the decorate() method
     *
     * @dataProvider getRoleFixtures
     */
    public function testDecorateReturnsCorrectValue(RoleInterface $role, $expected)
    {
        $this->assertEquals($expected, $this->getDecorator()->decorate($role));
    }

    /**
     * Gets Role instances for data fixtures
     */
    public function getRoleFixtures()
    {
        return [
            [new Role('ROLE_USER'), 'Tickit Login'],
            [new Role('ROLE_ADMIN'), 'Tickit Administrator'],
            [new Role('ROLE_SUPER_ADMIN'), 'Tickit Super Administrator'],
            [new Role('ROLE_ALLOWED_TO_SWITCH'), 'Tickit Switch User']
        ];
    }

    private function getDecorator()
    {
        return new RoleDecorator();
    }
}

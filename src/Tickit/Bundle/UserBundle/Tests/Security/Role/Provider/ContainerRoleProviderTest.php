<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
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

namespace Tickit\Bundle\UserBundle\Tests\Security\Role\Provider;

use Symfony\Component\Security\Core\Role\Role;
use Tickit\Bundle\UserBundle\Security\Role\Provider\ContainerRoleProvider;
use Tickit\Component\Model\User\User;

/**
 * ContainerRoleProvider tests
 *
 * @package Tickit\Bundle\UserBundle\Tests\Security\Role\Provider
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ContainerRoleProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $hierarchy;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->hierarchy = $this->getMockForAbstractClass('\Symfony\Component\Security\Core\Role\RoleHierarchyInterface');
    }

    /**
     * Tests the getAllRoles() method
     */
    public function testGetAllRolesFetchesRolesFromHierarchy()
    {
        $returnedRoles = [
            new Role('ROLE_USER'),
            new Role('ROLE_ADMIN')
        ];

        $this->hierarchy->expects($this->once())
                        ->method('getReachableRoles')
                        ->with([new Role(User::ROLE_SUPER_ADMIN)])
                        ->will($this->returnValue($returnedRoles));

        $this->assertEquals($returnedRoles, $this->getProvider()->getAllRoles());
    }

    /**
     * Tests the getReachableRolesForRole() method
     *
     * @dataProvider getReachableRolesFixtures
     */
    public function testGetReachableRolesForRoleFetchesCorrectlyFromHierarchy($returnedRoles, $searchRole, $expectedArg)
    {
        $this->hierarchy->expects($this->once())
                        ->method('getReachableRoles')
                        ->with($expectedArg)
                        ->will($this->returnValue($returnedRoles));

        $this->assertEquals($returnedRoles, $this->getProvider()->getReachableRolesForRole($searchRole));
    }

    public function getReachableRolesFixtures()
    {
        $roleUser = new Role('ROLE_USER');
        $roleAdmin = new Role('ROLE_ADMIN');
        $roleSuperAdmin = new Role('ROLE_SUPER_ADMIN');

        return [
            [
                [$roleUser, $roleAdmin], $roleAdmin, [$roleAdmin]
            ],
            [
                [$roleUser, $roleAdmin], [$roleUser, $roleAdmin], [$roleUser, $roleAdmin]
            ],
            [
                [$roleUser, $roleAdmin, $roleSuperAdmin], ['ROLE_ADMIN', 'ROLE_SUPER_ADMIN'], [$roleAdmin, $roleSuperAdmin]
            ]
        ];
    }

    /**
     * Gets a new instance
     *
     * @return ContainerRoleProvider
     */
    private function getProvider()
    {
        return new ContainerRoleProvider($this->hierarchy);
    }
}

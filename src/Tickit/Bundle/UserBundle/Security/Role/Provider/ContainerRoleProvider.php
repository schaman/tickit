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

namespace Tickit\Bundle\UserBundle\Security\Role\Provider;

use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Tickit\Component\Security\Role\Provider\RoleProviderInterface;
use Tickit\Component\Model\User\User;

/**
 * Role provider that sources from the service container.
 *
 * @package Tickit\Bundle\UserBundle\Security\Role\Provider
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ContainerRoleProvider implements RoleProviderInterface
{
    /**
     * A role hierarchy
     *
     * @var RoleHierarchyInterface
     */
    private $hierarchy;

    /**
     * Constructor.
     *
     * @param RoleHierarchyInterface $roleHierarchy
     */
    public function __construct(RoleHierarchyInterface $roleHierarchy)
    {
        $this->hierarchy = $roleHierarchy;
    }

    /**
     * Fetches an array of all available roles.
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->hierarchy->getReachableRoles([new Role(User::ROLE_DEFAULT)]);
    }
}

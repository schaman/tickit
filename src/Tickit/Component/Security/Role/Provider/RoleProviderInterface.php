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

namespace Tickit\Component\Security\Role\Provider;

use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Role provider interface.
 *
 * A role provider is responsible for providing a collection
 * of available roles from a data source.
 *
 * @package Tickit\Component\Security\Role\Provider
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface RoleProviderInterface
{
    /**
     * Fetches an array of all available roles.
     *
     * @return RoleInterface[]
     */
    public function getAllRoles();

    /**
     * Fetches an array of roles reachable from a role.
     *
     * For example, if the role hierarchy is something like:
     *
     *  - ROLE_ADMIN: [ROLE_USER]
     *  - ROLE_SUPER_ADMIN: [ROLE_ADMIN, ROLE_SWITCH]
     *
     * The this method would return ROLE_SUPER_ADMIN, ROLE_ADMIN,
     * ROLE_SWITCH and ROLE_USER when given "ROLE_SUPER_ADMIN" role.
     *
     * @param RoleInterface|RoleInterface[] $role The role(s) to find reachable roles for
     *
     * @return RoleInterface[]
     */
    public function getReachableRolesForRole($role);
}

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

namespace Tickit\Component\Security\Role\Decorator;

use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Role decorator.
 *
 * Responsible for decorating system role names into more
 * friendly display names.
 *
 * @package Tickit\Component\Security\Role\Decorator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class RoleDecorator implements RoleDecoratorInterface
{
    /**
     * A map of role names to friendly names
     *
     * @var array
     */
    private $map;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->hydrateMap();
    }

    /**
     * Decorates a role into a more friendly string
     *
     * @param RoleInterface $role The role to decorate
     *
     * @throws \OutOfBoundsException If the provided role is not contained in the map
     *
     * @return string
     */
    public function decorate(RoleInterface $role)
    {
        if (false === isset($this->map[$role->getRole()])) {
            throw new \OutOfBoundsException(
                sprintf('Provided role (%s) was not found in the role map', $role->getRole())
            );
        }

        return $this->map[$role->getRole()];
    }

    /**
     * Hydrates the map of roles.
     */
    private function hydrateMap()
    {
        $this->map = [
            'ROLE_USER'              => 'Tickit Login',
            'ROLE_ADMIN'             => 'Tickit Administrator',
            'ROLE_SUPER_ADMIN'       => 'Tickit Super Administrator',
            'ROLE_ALLOWED_TO_SWITCH' => 'Tickit Switch User'
        ];
    }
}

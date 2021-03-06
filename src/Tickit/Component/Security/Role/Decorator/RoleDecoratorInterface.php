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

namespace Tickit\Component\Security\Role\Decorator;

use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Role decorator interface.
 *
 * Role decorators are responsible for decorating system role names
 * into various formats.
 *
 * @package Tickit\Component\Security\Role\Decorator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface RoleDecoratorInterface
{
    /**
     * Decorates a role
     *
     * @param RoleInterface $role The role to decorate
     *
     * @return mixed
     */
    public function decorate(RoleInterface $role);
}

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

namespace Tickit\Component\Decorator\Entity;

/**
 * Entity decorator interfaces.
 *
 * Entity decorators are responsible for decorating an entity
 * instance into a scalar representation for form picker views.
 *
 * @package Tickit\Component\Decorator\Entity
 */
interface EntityDecoratorInterface
{
    /**
     * Decorates an entity with a scalar representation
     *
     * @param mixed $entity The entity to decorate
     *
     * @throws \InvalidArgumentException If the $entity argument isn't the correct instance
     *
     * @return mixed The decorated value
     */
    public function decorate($entity);
}

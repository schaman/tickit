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

namespace Tickit\Component\Decorator;

/**
 * Domain object decorator interface.
 *
 * Domain object decorators are responsible for taking a domain object and
 * decorating it using a specific format.
 *
 * @package Tickit\Component\Decorator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface DomainObjectDecoratorInterface
{
    /**
     * Decorates an object using the specified property names
     *
     * @param object $object           The domain object to decorate
     * @param array  $propertyNames    The property names used in the decoration
     * @param array  $staticProperties Any additional static properties that should be appended to each result
     *                                 (indexed by property name)
     *
     * @return mixed
     */
    public function decorate($object, array $propertyNames, array $staticProperties = array());
}

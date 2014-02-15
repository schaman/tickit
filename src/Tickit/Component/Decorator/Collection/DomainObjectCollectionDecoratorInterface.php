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

namespace Tickit\Component\Decorator\Collection;

/**
 * File description
 *
 * @package Tickit\Component\Decorator\Collection
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface DomainObjectCollectionDecoratorInterface
{
    /**
     * Decorates a collection of domain objects and returns the result.
     *
     * @param array|\ArrayIterator $data             The domain objects to be decorated
     * @param array                $propertyNames    Property names of the domain objects to include in the decorated result
     * @param array                $staticProperties The static properties to attach to each decorated result
     *
     * @return array
     */
    public function decorate($data, array $propertyNames, array $staticProperties = array());
}

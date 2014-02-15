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

namespace Tickit\Component\Filter\Map;

use Tickit\Component\Filter\Map\Definition\FilterDefinition;

/**
 * Filter Mapper interface.
 *
 * Filter mappers are responsible for returning a map of field names
 * to the filter types that they should implement.
 *
 * @package Tickit\Component\Filter\Map
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @see     Tickit\Component\Filter\Collection\Builder\FilterCollectionBuilder
 * @see     Tickit\Component\Filter\Map\Definition\FilterDefinitionInterface
 */
interface FilterMapperInterface
{
    /**
     * Gets the field map.
     *
     * Returns the mapping of field names to filter types.
     *
     * This is usually in the form of an array, where the keys are
     * the field names and the values instances of FilterDefinition
     *
     * @return FilterDefinition[]
     */
    public function getFieldMap();
}

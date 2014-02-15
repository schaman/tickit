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

namespace Tickit\Component\Filter\Map\Definition;

/**
 * Filter Definition interface.
 *
 * Filter definitions provide a way of describing a filter against
 * a field without instantiating the filter directly.
 *
 * @package Tickit\Component\Filter\Map\Definition
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @see     Tickit\Component\Filter\Collection\FilterCollectionBuilder
 */
interface FilterDefinitionInterface
{
    /**
     * Gets the filter type
     *
     * @return string
     */
    public function getType();

    /**
     * Gets an array of options for the filter
     *
     * @return array
     */
    public function getOptions();
}

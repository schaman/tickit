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

namespace Tickit\Component\Filter\Map\Client;

use Tickit\Component\Filter\AbstractFilter;
use Tickit\Component\Filter\Map\Definition\FilterDefinition;
use Tickit\Component\Filter\Map\FilterMapperInterface;

/**
 * Client filter mapper
 *
 * Responsible for mapping fields to filter types
 *
 * @package Tickit\Component\Filter\Map\Client
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientFilterMapper implements FilterMapperInterface
{
    /**
     * Gets the field map.
     *
     * Returns the mapping of field names to filter types.
     *
     * @return FilterDefinition[]
     */
    public function getFieldMap()
    {
        return [
            'name' => new FilterDefinition(AbstractFilter::FILTER_SEARCH),
            'status' => new FilterDefinition(AbstractFilter::FILTER_EXACT_MATCH)
        ];
    }
}
 
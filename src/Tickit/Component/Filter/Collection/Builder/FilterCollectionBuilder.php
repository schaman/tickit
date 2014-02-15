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

namespace Tickit\Component\Filter\Collection\Builder;

use Tickit\Component\Filter\AbstractFilter;
use Tickit\Component\Filter\Collection\FilterCollection;
use Tickit\Component\Filter\Map\Definition\FilterDefinition;
use Tickit\Component\Filter\Map\FilterMapperInterface;

/**
 * Filter collection builder.
 *
 * Responsible for building a collection of filters.
 *
 * @package Tickit\Component\Filter\Collection\Builder
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @see     Tickit\Component\Filter\Collection\FilterCollection
 */
class FilterCollectionBuilder
{
    /**
     * Builds a collection of filters from an array of data
     *
     * @param array                 $data         The array to build from
     * @param FilterMapperInterface $filterMapper A filter mapper
     * @param string                $joinType   The filter type, either "AND" or "OR". If the filter type is "OR" then
     *                                          the resultant FilterCollection will bind all filters using OR logic,
     *                                          meaning that only one of the conditions has to be true to return a match.
     *                                          Defaults to "AND"
     *
     * @return FilterCollection
     */
    public function buildFromArray(
        array $data,
        FilterMapperInterface $filterMapper,
        $joinType = FilterCollection::JOIN_TYPE_AND
    ) {
        $collection = new FilterCollection();
        $fieldMap = $filterMapper->getFieldMap();

        foreach ($data as $fieldName => $filterValue) {
            $definition = (isset($fieldMap[$fieldName])) ? $fieldMap[$fieldName] : null;

            if (!$definition instanceof FilterDefinition) {
                // we can't filter on a field when we don't know it's filter type, so skip it
                // An alternative here might be to treat it like an ExactMatchFilter, but we
                // need to expect that non-valid fields could be passed in the request
                continue;
            }

            $options = array_replace(
                ['joinType' => $joinType],
                $definition->getOptions()
            );

            $filter = AbstractFilter::factory($definition->getType(), $fieldName, $filterValue, $options);
            $collection->add($filter);
        }

        return $collection;
    }
}

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

namespace Tickit\Component\Filter\Collection\Builder;

use Symfony\Component\HttpFoundation\Request;
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
 */
class FilterCollectionBuilder
{
    /**
     * Builds a collection of filters from a request object
     *
     * @param Request               $request         The request object
     * @param string                $filterNamespace The namespace key of the filters in the request object
     * @param FilterMapperInterface $filterMapper    A filter mapper
     *
     * @return FilterCollection
     */
    public function buildFromRequest(Request $request, $filterNamespace, FilterMapperInterface $filterMapper)
    {
        $collection = new FilterCollection();
        $fieldMap = $filterMapper->getFieldMap();

        $requestFilters = $request->query->get($filterNamespace, []);
        foreach ($requestFilters as $fieldName => $filterValue) {
            $definition = (isset($fieldMap[$fieldName])) ? $fieldMap[$fieldName] : null;

            if (!$definition instanceof FilterDefinition) {
                // we can't filter on a field when we don't know it's filter type, so skip it
                // An alternative here might be to treat it like an ExactMatchFilter, but we
                // need to expect that non-valid fields could be passed in the request
                continue;
            }

            $filter = AbstractFilter::factory(
                $definition->getType(),
                $fieldName,
                $filterValue,
                $definition->getOptions()
            );
            $collection->add($filter);
        }

        return $collection;
    }
}

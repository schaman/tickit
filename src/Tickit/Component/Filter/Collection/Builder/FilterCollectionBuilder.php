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

use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Request;
use Tickit\Component\Filter\Collection\FilterCollection;
use Tickit\Component\Filter\ExactMatchFilter;
use Tickit\Component\Filter\OrderByFilter;
use Tickit\Component\Filter\SearchFilter;

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
    const FILTER_SEARCH = 'search';
    const FILTER_EXACT_MATCH = 'exactMatch';
    const FILTER_ORDER_BY = 'orderBy';

    /**
     * Builds a collection of filters from a request object
     *
     * @param Request $request The request object
     *
     * @return FilterCollection
     */
    public function buildFromRequest(Request $request)
    {
        $collection = new FilterCollection();

        foreach ($this->getFilterTypes() as $type) {
            $filters = $request->query->get($type, array());
            $this->addFilters($filters, $type, $collection);
        }

        return $collection;
    }

    /**
     * Adds filters of a given type to a collection
     *
     * @param array      $filters    An array of filter values
     * @param string     $type       The type of the filters being added
     * @param Collection $collection The collection to add the filters to
     *
     * @return void
     */
    protected function addFilters(array $filters, $type, Collection &$collection)
    {
        foreach ($filters as $key => $value) {
            switch ($type) {
                case static::FILTER_EXACT_MATCH:
                    $filter = new ExactMatchFilter($key, $value);
                    break;
                case static::FILTER_ORDER_BY:
                    $filter = new OrderByFilter($key, $value);
                    break;
                default:
                    $filter = new SearchFilter($key, $value);
            }

            $collection->add($filter);
        }
    }

    /**
     * Gets an array of valid filter types
     *
     * @return array
     */
    private function getFilterTypes()
    {
        return array(
            static::FILTER_SEARCH,
            static::FILTER_EXACT_MATCH,
            static::FILTER_ORDER_BY
        );
    }
}

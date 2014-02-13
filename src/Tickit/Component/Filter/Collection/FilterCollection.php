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

namespace Tickit\Component\Filter\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Tickit\Component\Filter\QueryBuilderApplicableInterface;

/**
 * Filter collection.
 *
 * Provides a collection wrapper for filter objects.
 *
 * @package Tickit\Component\Filter\Collection
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class FilterCollection extends ArrayCollection
{
    const JOIN_TYPE_AND = 'AND';
    const JOIN_TYPE_OR = 'OR';

    /**
     * The join type.
     *
     * When this value is "AND", all filters will be applied to the query
     * in such a way that all filter conditions must be met in order for a
     * result to be returned.
     *
     * If this value is "OR" then only one of the filter conditions must be
     * true in order to return a match.
     *
     * @var string
     */
    private $joinType = 'AND';

    /**
     * Sets the filters type
     */
    public function setType($type)
    {
        if (false === in_array($type, [static::JOIN_TYPE_AND, static::JOIN_TYPE_OR])) {
            throw new \InvalidArgumentException(sprintf('Invalid join type (%s) provided', $type));
        }

        $this->joinType = $type;
    }

    /**
     * Applies the collection of filters to a query
     *
     * @param QueryBuilder $query The query to apply the filter collection to
     *
     * @return void
     */
    public function applyToQuery(QueryBuilder &$query)
    {
        /** @var QueryBuilderApplicableInterface $filter */
        foreach ($this->toArray() as $filter) {
            $filter->applyToQuery($query, $this->joinType);
        }
    }
}

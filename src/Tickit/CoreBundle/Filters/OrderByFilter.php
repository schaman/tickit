<?php

/*
 * Tickit, an source web based bug management tool.
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

namespace Tickit\CoreBundle\Filters;

use Doctrine\ORM\QueryBuilder;
use Tickit\CoreBundle\Filters\Collection\Builder\FilterCollectionBuilder;

/**
 * OrderBy filter.
 *
 * An order by filter is used to add directional sorting on a query.
 *
 * @package Tickit\CoreBundle\Filters
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class OrderByFilter extends AbstractFilter
{
    const DIR_ASC = 'ASC';
    const DIR_DESC = 'DESC';

    /**
     * Applies the itself to a query builder.
     *
     * @param QueryBuilder $query A reference to the query builder
     *
     * @return void
     */
    public function applyToQuery(QueryBuilder &$query)
    {
        if (false === $this->filterKeyIsValidOnQuery($query, $this->getKey())) {
            return;
        }

        $aliases = $query->getRootAliases();

        $validDirections = array(static::DIR_ASC, static::DIR_DESC);
        $direction = in_array($this->getValue(), $validDirections) ? $this->getValue() : static::DIR_DESC;

        $query->addOrderBy(sprintf('%s.%s', $aliases[0], $this->getKey()), $direction);
    }

    /**
     * Returns the filter type
     *
     * @return string
     */
    public function getType()
    {
        return FilterCollectionBuilder::FILTER_ORDER_BY;
    }
}

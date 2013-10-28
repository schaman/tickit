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

namespace Tickit\Bundle\CoreBundle\Filters;

use Doctrine\ORM\QueryBuilder;
use Tickit\Bundle\CoreBundle\Filters\Collection\Builder\FilterCollectionBuilder;

/**
 * Exact match filter.
 *
 * An exact match filter represents an exact match filter.
 *
 * @package Tickit\Bundle\CoreBundle\Filters\Model
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ExactMatchFilter extends AbstractFilter
{
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

        $query->andWhere(sprintf('%s.%s = :%s', $aliases[0], $this->getKey(), $this->getKey()))
              ->setParameter($this->getKey(), $this->getValue());
    }

    /**
     * Returns the filter type
     *
     * @return string
     */
    public function getType()
    {
        return FilterCollectionBuilder::FILTER_EXACT_MATCH;
    }
}

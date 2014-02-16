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

namespace Tickit\Component\Filter;

use Doctrine\ORM\QueryBuilder;
use Tickit\Component\Filter\Collection\FilterCollection;

/**
 * Search filter.
 *
 * A search filter represents a text based search with wildcard support
 *
 * @package Tickit\Component\Filter\Model
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class SearchFilter extends AbstractFilter
{
    /**
     * Applies the itself to a query builder.
     *
     * @param QueryBuilder $query A reference to the query builder
     */
    public function applyToQuery(QueryBuilder &$query)
    {
        if (false === $this->filterKeyIsValidOnQuery($query, $this->getKey())) {
            return;
        }

        $value = $this->getValue();
        if (empty($value)) {
            return;
        }

        $aliases = $query->getRootAliases();

        $column = sprintf('%s.%s', $aliases[0], $this->getKey());
        $like = $query->expr()->like($column, sprintf(':%s', $this->getKey()));

        if ($this->getJoinType() === FilterCollection::JOIN_TYPE_AND) {
            $query->andWhere($like);
        } else {
            $query->orWhere($like);
        }

        $query->setParameter($this->getKey(), '%' . $this->getValue() . '%');
    }

    /**
     * Returns the filter type
     *
     * @return string
     */
    public function getType()
    {
        return AbstractFilter::FILTER_SEARCH;
    }
}

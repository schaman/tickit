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

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\QueryBuilder;

/**
 * Exact match filter.
 *
 * An exact match filter represents an exact match filter.
 *
 * @package Tickit\Component\Filter\Model
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

        $value = $this->getValue();
        if (empty($value)) {
            return;
        }

        $aliases = $query->getRootAliases();

        if ($value instanceof Collection || true === is_array($value)) {
            $values = [];
            // TODO: this needs refactoring to use named parameters rather than numeric parameters
            $i = count($query->getParameters()) + 1;
            foreach ($value as $v) {
                $values[sprintf('?%d', $i++)] = $v;
            }
            $query->andWhere(
                $query->expr()->in(
                    sprintf('%s.%s', $aliases[0], $this->getKey()),
                    array_keys($values)
                )
            );
            foreach ($values as $key => $value) {
                $query->setParameter(str_replace('?', '', $key), $value);
            }
        } else {
            $query->andWhere(sprintf('%s.%s %s :%s', $aliases[0], $this->getKey(), $this->getComparator(), $this->getKey()))
                  ->setParameter($this->getKey(), $this->getValue());
        }
    }

    /**
     * Returns the filter type
     *
     * @return string
     */
    public function getType()
    {
        return AbstractFilter::FILTER_EXACT_MATCH;
    }
}

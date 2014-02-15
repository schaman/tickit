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

/**
 * CallbackFilter
 *
 * @package Tickit\Component\Filter
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class CallbackFilter extends AbstractFilter
{
    /**
     * Returns the filter type
     *
     * @return string
     */
    public function getType()
    {
        return static::FILTER_CALLBACK;
    }

    /**
     * Applies itself to a query builder.
     *
     * @param QueryBuilder $query A reference to the query builder
     *
     * @throws \InvalidArgumentException If the callback option is not a valid callable
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

        $callback = $this->getOption('callback');

        if (!is_callable($callback)) {
            throw new \InvalidArgumentException(
                sprintf('An invalid callable type (%s) was provided in %s', gettype($callback), __CLASS__)
            );
        }

        call_user_func($callback, $query, $value, $this->getJoinType());
    }
}

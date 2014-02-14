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
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Tickit\Component\Filter\Collection\FilterCollection;

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
     * @param QueryBuilder $query    A reference to the query builder
     * @param string       $joinType The join type, either "AND" or "OR"
     *
     * @return void
     */
    public function applyToQuery(QueryBuilder &$query, $joinType)
    {
        if (false === $this->filterKeyIsValidOnQuery($query, $this->getKey())) {
            return;
        }

        $value = $this->getValue();
        if ($value instanceof Collection) {
            $value = $value->toArray();
        }

        if (empty($value)) {
            return;
        }

        $aliases = $query->getRootAliases();

        if (true === is_array($value)) {
            $this->bindConditionForMultipleValues($query, $this->getKey(), $value, $joinType, $aliases[0]);
        } else {
            $this->bindConditionForSingleValue($query, $this->getKey(), $value, $joinType, $aliases[0]);
        }
    }

    /**
     * Binds a condition for multiple value matches
     *
     * @param QueryBuilder     $query     The query to bind the condition to
     * @param string           $key       The property key that we are filtering on
     * @param array|Collection $values    The values that we are filtering for
     * @param string           $joinType  The join type (either "OR" or "AND")
     * @param string           $rootAlias The query's root object alias
     */
    private function bindConditionForMultipleValues(QueryBuilder $query, $key, $values, $joinType, $rootAlias)
    {
        // here we have to dynamically build a WHERE (cond1 OR cond2 OR cond3 ...)
        // query condition using doctrine's Expr class, because the paginators in
        // doctrine have quirks with WHERE IN query conditions because of the way
        // it handles parameters in the paginator
        $expression = $query->expr();
        $valueCopy = $values;
        array_walk(
            $valueCopy,
            function (&$v, $i) use ($expression, $key, $rootAlias) {
                /** @var Expr $expression */
                $parameterName = sprintf(':%s%d', $key, $i);
                $property = sprintf('%s.%s', $rootAlias, $key);
                $v = $expression->eq($property, $parameterName);
            }
        );

        $condition = call_user_func_array(array($expression, 'orX'), $valueCopy);
        $this->bindConditionToQuery($query, $condition, $joinType);

        $i = 0;
        foreach ($values as $subValue) {
            $query->setParameter(sprintf('%s%d', $this->getKey(), $i++), $subValue);
        }
    }

    /**
     * Binds a condition for a single value match
     *
     * @param QueryBuilder     $query     The query to bind the condition to
     * @param string           $key       The property key that we are filtering on
     * @param mixed            $value     The value that we are filtering for
     * @param string           $joinType  The join type (either "OR" or "AND")
     * @param string           $rootAlias The query's root object alias
     */
    private function bindConditionForSingleValue(QueryBuilder $query, $key, $value, $joinType, $rootAlias)
    {
        $condition = sprintf('%s.%s %s :%s', $rootAlias, $this->getKey(), $this->getComparator(), $this->getKey());
        $this->bindConditionToQuery($query, $condition, $joinType);
        $query->setParameter($key, $value);
    }

    /**
     * Binds a condition to the query using the given $joinType
     *
     * @param QueryBuilder $query     The query to bind the condition to
     * @param mixed        $condition The query condition to bind
     * @param string       $joinType  The join type used to bind the condition (either "AND" or "OR")
     */
    private function bindConditionToQuery(QueryBuilder $query, $condition, $joinType)
    {
        if ($joinType === FilterCollection::JOIN_TYPE_AND) {
            $query->andWhere($condition);
        } else {
            $query->orWhere($condition);
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

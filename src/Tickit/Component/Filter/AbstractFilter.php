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
 * Abstract filter implementation.
 *
 * @package Tickit\Component\Filter
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractFilter implements QueryBuilderApplicableInterface
{
    const FILTER_SEARCH = 'search';
    const FILTER_EXACT_MATCH = 'exactMatch';
    const FILTER_ORDER_BY = 'orderBy';
    const FILTER_CALLBACK = 'callback';

    const COMPARATOR_EQUAL = '=';
    const COMPARATOR_GREATER_THAN = '>';
    const COMPARATOR_LESS_THAN = '<';
    const COMPARATOR_LESS_THAN_OR_EQUAL_TO = '<=';
    const COMPARATOR_GREATER_THAN_OR_EQUAL_TO = '>=';

    /**
     * The value of this filter
     *
     * @var mixed
     */
    protected $value;

    /**
     * The filter key.
     *
     * Usually this is the name of the column that is being filtered
     *
     * @var string
     */
    protected $key;

    /**
     * An array of filter options
     *
     * @var array
     */
    protected $options;

    /**
     * Constructor
     *
     * @param string $key     The filter key
     * @param mixed  $value   The filter value
     * @param array  $options An array of filters options (optional)
     */
    public function __construct($key, $value, array $options = [])
    {
        $this->key   = $key;
        $this->value = $value;
        $this->options = $options;
    }

    /**
     * Gets the filter value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Gets the filter key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Gets an option by it's name
     *
     * @param string $name     The option name to fetch
     * @param mixed  $fallback The value that will be returned if the option requested does not exist
     *                         (defaults to null)
     *
     * @return mixed
     */
    public function getOption($name, $fallback = null)
    {
        if (!isset($this->options[$name])) {
            return $fallback;
        }

        return $this->options[$name];
    }

    /**
     * Factory method for creating a new filter object
     *
     * @param string $type    The type of filter to create
     * @param string $key     The filter key
     * @param mixed  $value   The filter value
     * @param array  $options An array of filter options (optional)
     *
     * @throws \InvalidArgumentException If an invalid $type value is provided
     *
     * @return AbstractFilter
     */
    public static function factory($type, $key, $value, array $options = [])
    {
        switch ($type) {
            case static::FILTER_EXACT_MATCH:
                $filter = new ExactMatchFilter($key, $value, $options);
                break;
            case static::FILTER_ORDER_BY:
                $filter = new OrderByFilter($key, $value, $options);
                break;
            case static::FILTER_SEARCH:
                $filter = new SearchFilter($key, $value, $options);
                break;
            case static::FILTER_CALLBACK:
                $filter = new CallbackFilter($key, $value, $options);
                break;
            default:
                throw new \InvalidArgumentException(
                    sprintf('An invalid filter type (%s) was provided', $type)
                );
        }

        return $filter;
    }

    /**
     * Returns the filter type
     *
     * @return string
     */
    abstract public function getType();

    /**
     * Returns true if the given filter key is valid on the provided query.
     *
     * If the filter key does not exist as a property on the root entity in
     * the query, then this method will return false.
     *
     * @param QueryBuilder $query The query builder to validate on
     * @param string       $key   The filter key to validate
     *
     * @return boolean
     */
    protected function filterKeyIsValidOnQuery(QueryBuilder $query, $key)
    {
        $entities = $query->getRootEntities();
        $entity = array_shift($entities);

        if (empty($entity)) {
            return false;
        }

        $classMeta = $query->getEntityManager()->getClassMetadata($entity);
        $reflection = new \ReflectionClass($classMeta->name);

        try {
            $reflection->getProperty($key);

            return true;
        } catch (\ReflectionException $e) {
            return false;
        }
    }

    /**
     * Gets the comparator option on the filter
     *
     * @return string
     */
    protected function getComparator()
    {
        $comparator = $this->getOption('comparator', static::FILTER_EXACT_MATCH);

        $validComparators = [
            static::COMPARATOR_EQUAL,
            static::COMPARATOR_GREATER_THAN,
            static::COMPARATOR_GREATER_THAN_OR_EQUAL_TO,
            static::COMPARATOR_LESS_THAN,
            static::COMPARATOR_LESS_THAN_OR_EQUAL_TO
        ];

        if (false === in_array($comparator, $validComparators)) {
            return static::COMPARATOR_EQUAL;
        }

        return $comparator;
    }

    /**
     * Gets the join type option for this filter
     *
     * @return string
     */
    protected function getJoinType()
    {
        return $this->getOption('joinType', FilterCollection::JOIN_TYPE_AND);
    }
}

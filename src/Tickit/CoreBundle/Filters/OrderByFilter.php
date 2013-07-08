<?php

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
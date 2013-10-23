<?php

namespace Tickit\CoreBundle\Filters;

use Doctrine\ORM\QueryBuilder;
use Tickit\CoreBundle\Filters\Collection\Builder\FilterCollectionBuilder;

/**
 * Exact match filter.
 *
 * An exact match filter represents an exact match filter.
 *
 * @package Tickit\CoreBundle\Filters\Model
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

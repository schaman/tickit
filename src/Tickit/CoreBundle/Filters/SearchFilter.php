<?php

namespace Tickit\CoreBundle\Filters;

use Doctrine\ORM\QueryBuilder;
use Tickit\CoreBundle\Filters\Collection\Builder\FilterCollectionBuilder;

/**
 * Search filter.
 *
 * A search filter represents a text based search with wildcard support
 *
 * @package Tickit\CoreBundle\Filters\Model
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class SearchFilter extends AbstractFilter
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

        $column = sprintf('%s.%s', $aliases[0], $this->getKey());
        $query->andWhere($query->expr()->like($column, sprintf(':%s', $this->getKey())))
              ->setParameter($this->getKey(), '%' . $this->getValue() . '%');
    }

    /**
     * Returns the filter type
     *
     * @return string
     */
    public function getType()
    {
        return FilterCollectionBuilder::FILTER_SEARCH;
    }
}

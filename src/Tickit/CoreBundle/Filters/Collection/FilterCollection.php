<?php

namespace Tickit\CoreBundle\Filters\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Tickit\CoreBundle\Filters\QueryBuilderApplicableInterface;

/**
 * Filter collection.
 *
 * Provides a collection wrapper for filter objects.
 *
 * @package Tickit\CoreBundle\Filters\Collection
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class FilterCollection extends ArrayCollection
{
    /**
     * Applies the collection of filters to a query
     *
     * @param QueryBuilder $query The query to apply the filter collection to
     *
     * @return void
     */
    public function applyToQuery(QueryBuilder &$query)
    {
        /** @var QueryBuilderApplicableInterface $filter */
        foreach ($this->toArray() as $filter) {
            $filter->applyToQuery($query);
        }
    }
}

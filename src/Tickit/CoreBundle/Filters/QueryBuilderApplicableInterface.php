<?php

namespace Tickit\CoreBundle\Filters;

use Doctrine\ORM\QueryBuilder;

/**
 * Query builder applicable interface.
 *
 * A query builder applicable object is something that can be applied to a query builder.
 *
 * @package Tickit\CoreBundle\Filters
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface QueryBuilderApplicableInterface
{
    /**
     * Applies the itself to a query builder.
     *
     * @param QueryBuilder $query A reference to the query builder
     *
     * @return void
     */
    public function applyToQuery(QueryBuilder &$query);
}

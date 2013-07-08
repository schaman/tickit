<?php

namespace Tickit\CoreBundle\Entity\Repository;

use Tickit\CoreBundle\Filters\Collection\FilterCollection;

/**
 * Filterable repository interface.
 *
 * A filterable repository has the ability to return results based on
 * a set of filters.
 *
 * @package Tickit\CoreBundle\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @see     Tickit\CoreBundle\Filters\FilterCollection
 */
interface FilterableRepositoryInterface
{
    /**
     * Finds results based off a set of filters.
     *
     * @param FilterCollection $filters The filter collection
     *
     * @return mixed
     */
    public function findByFilters(FilterCollection $filters);
}

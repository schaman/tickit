<?php

namespace Tickit\UserBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Tickit\CoreBundle\Entity\Repository\FilterableRepositoryInterface;
use Tickit\CoreBundle\Filters\Collection\FilterCollection;

/**
 * Group repository.
 *
 * Provides methods for fetching group related data from the entity manager.
 *
 * @package Tickit\UserBundle\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class GroupRepository extends EntityRepository implements FilterableRepositoryInterface
{
    /**
     * Finds results based off a set of filters.
     *
     * @param FilterCollection $filters The filter collection
     *
     * @return mixed
     */
    public function findByFilters(FilterCollection $filters)
    {
        $query = $this->createQueryBuilder('g');

        $filters->applyToQuery($query);

        return $query->getQuery()->execute();
    }
}

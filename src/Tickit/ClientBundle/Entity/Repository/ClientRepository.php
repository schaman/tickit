<?php

namespace Tickit\ClientBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Tickit\CoreBundle\Filters\Collection\FilterCollection;

/**
 * Client repository.
 *
 * Provides methods for fetching client data.
 *
 * @package Tickit\ClientBundle\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientRepository extends EntityRepository
{
    /**
     * Finds results based off a set of filters.
     *
     * @param FilterCollection $filters The filter collection
     *
     * @codeCoverageIgnore
     *
     * @return mixed
     */
    public function findByFilters(FilterCollection $filters)
    {
        return $this->getFindByFiltersQueryBuilder($filters)->getQuery()->execute();
    }

    /**
     * Gets the query builder for finding a filtered set of Clients
     *
     * @param FilterCollection $filters The filter collection
     *
     * @return QueryBuilder
     */
    public function getFindByFiltersQueryBuilder(FilterCollection $filters)
    {
        $queryBuilder = $this->getEntityManager()
                             ->createQueryBuilder()
                             ->select('c')
                             ->from('TickitClientBundle:Client', 'c');

        $filters->applyToQuery($queryBuilder);

        return $queryBuilder;
    }
}

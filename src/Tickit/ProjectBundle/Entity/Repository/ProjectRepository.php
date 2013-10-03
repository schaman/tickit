<?php

namespace Tickit\ProjectBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Tickit\CoreBundle\Entity\Repository\FilterableRepositoryInterface;
use Tickit\CoreBundle\Filters\Collection\FilterCollection;

/**
 * Project entity repository.
 *
 * Provides methods for retrieving Project related data from the data layer
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectRepository extends EntityRepository implements FilterableRepositoryInterface
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
     * Gets the query builder for finding a filtered set of Projects
     *
     * @param FilterCollection $filters The filter collection
     *
     * @return QueryBuilder
     */
    public function getFindByFiltersQueryBuilder(FilterCollection $filters)
    {
        $queryBuilder = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('p')
            ->from('TickitProjectBundle:Project', 'p');

        $filters->applyToQuery($queryBuilder);

        return $queryBuilder;
    }
}

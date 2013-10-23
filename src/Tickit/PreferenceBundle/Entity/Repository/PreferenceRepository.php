<?php

namespace Tickit\PreferenceBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Tickit\CoreBundle\Entity\Repository\FilterableRepositoryInterface;
use Tickit\CoreBundle\Filters\Collection\FilterCollection;

/**
 * Preference repository.
 *
 * Provides methods for fetching preference related data from the data layer.
 *
 * @package Tickit\PreferenceBundle\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PreferenceRepository extends EntityRepository implements FilterableRepositoryInterface
{
    /**
     * Finds all preferences and returns them indexed by their system name
     *
     * @param integer[] $exclusions An array of Preference ids to ignore
     *
     * @codeCoverageIgnore
     *
     * @return array
     */
    public function findAllWithExclusionsIndexedBySystemName(array $exclusions = array())
    {
        return $this->getFindAllWithExclusionsIndexedBySystemNameQueryBuilder($exclusions)->getQuery()->execute();
    }

    /**
     * Gets a query builder that finds all preferences and returns them by system name.
     *
     * @param array $exclusions An array of excluded preference IDs not to include in the result (optional)
     *
     * @return QueryBuilder
     */
    public function getFindAllWithExclusionsIndexedBySystemNameQueryBuilder(array $exclusions = array())
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder = $queryBuilder->select('p')
                                     ->from('TickitPreferenceBundle:Preference', 'p', 'p.systemName');

        if (!empty($exclusions)) {
            $queryBuilder->where($queryBuilder->expr()->notIn('p.id', $exclusions));
        }

        return $queryBuilder;
    }

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
     * Gets a query builder that returns a filtered set of preferences
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
                             ->from('TickitPreferenceBundle:Preference', 'p');

        $filters->applyToQuery($queryBuilder);

        return $queryBuilder;
    }
}

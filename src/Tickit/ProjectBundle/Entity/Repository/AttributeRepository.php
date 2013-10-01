<?php

namespace Tickit\ProjectBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Tickit\CoreBundle\Entity\Repository\FilterableRepositoryInterface;
use Tickit\CoreBundle\Filters\Collection\FilterCollection;

/**
 * Attribute entity repository.
 *
 * Provides methods for fetching project attributes from the data layer
 *
 * @package Tickit\ProjectBundle\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AttributeRepository extends EntityRepository implements FilterableRepositoryInterface
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
                             ->select('a')
                             ->from('TickitProjectBundle:AbstractAttribute', 'a');

        $filters->applyToQuery($queryBuilder);

        return $queryBuilder;
    }

    /**
     * Returns a deep collection of all project attributes
     *
     * This method includes all associated meta objects related to the attributes.
     *
     * @return mixed
     */
    public function findAllAttributes()
    {
        $choicesQuery = $this->getEntityManager()
                             ->createQueryBuilder()
                             ->select('c, ch')
                             ->from('TickitProjectBundle:ChoiceAttribute', 'c')
                             ->leftJoin('c.choices', 'ch');

        $choices = $choicesQuery->getQuery()->execute();

        $othersQuery = $this->getEntityManager()
                            ->createQueryBuilder()
                            ->select('a')
                            ->from('TickitProjectBundle:AbstractAttribute', 'a')
                            ->where(
                                'a INSTANCE OF TickitProjectBundle:LiteralAttribute OR
                                 a INSTANCE OF TickitProjectBundle:EntityAttribute'
                            );

        $others = $othersQuery->getQuery()->execute();

        return array_merge($others, $choices);
    }
}

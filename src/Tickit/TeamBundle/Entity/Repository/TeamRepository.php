<?php

namespace Tickit\TeamBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Tickit\CoreBundle\Entity\Repository\FilterableRepositoryInterface;
use Tickit\CoreBundle\Filters\Collection\FilterCollection;

/**
 * Team entity repository.
 *
 * Provides methods for retrieving Team related data from the data layer
 *
 * @package Tickit\TeamBundle\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TeamRepository extends EntityRepository implements FilterableRepositoryInterface
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
        $teamsQ = $this->getEntityManager()
                       ->createQueryBuilder()
                       ->select('p')
                       ->from('TickitTeamBundle:Team', 'p');

        $filters->applyToQuery($teamsQ);

        return $teamsQ->getQuery()->execute();
    }
}

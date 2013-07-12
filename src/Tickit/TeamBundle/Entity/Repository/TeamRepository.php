<?php

namespace Tickit\TeamBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Team entity repository.
 *
 * Provides methods for retrieving Team related data from the data layer
 *
 * @package Tickit\TeamBundle\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TeamRepository extends EntityRepository
{
    /**
     * Returns a collection of teams that match the given criteria
     *
     * @param array $filters An array of filters used to filter the result
     *
     * @return mixed
     */
    public function findByFilters(array $filters = array())
    {
        $teamsQ = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('p')
            ->from('TickitTeamBundle:Team', 'p');

        foreach ($filters as $column => $value) {
            if (is_string($value)) {
                $teamsQ->where(sprintf('%s LIKE :%s', $column, $column));
                $teamsQ->setParameter($column, $value);
            }
        }

        return $teamsQ->getQuery()->execute();
    }
}

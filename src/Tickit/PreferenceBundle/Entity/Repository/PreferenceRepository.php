<?php

namespace Tickit\PreferenceBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Preference repository.
 *
 * Provides methods for fetching preference related data from the data layer.
 *
 * @package Tickit\PreferenceBundle\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PreferenceRepository extends EntityRepository
{
    /**
     * Finds all preferences and returns them indexed by their system name
     *
     * @param integer[] $exclusions An array of Preference ids to ignore
     *
     * @return array
     */
    public function findAllWithExclusionsIndexedBySystemName(array $exclusions = array())
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $query = $qb->select('p')
                    ->from('TickitPreferenceBundle:Preference', 'p', 'p.systemName');

        if (!empty($exclusions)) {
            $query->where($qb->expr()->notIn('p.id', $exclusions));
        }

        return $query->getQuery()->execute();
    }
}

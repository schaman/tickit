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
     * @return array
     */
    public function findAllIndexedBySystemName()
    {
        $query = $this->createQueryBuilder('p')
                      ->from('TickitPreferenceBundle:Preference', 'p', 'p.systemName')
                      ->getQuery();

        return $query->execute();
    }
}

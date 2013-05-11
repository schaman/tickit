<?php

namespace Tickit\PermissionBundle\Entity\Repository;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Tickit\UserBundle\Entity\Group;

/**
 * Group Permission Value repository.
 *
 * Provides methods for retrieving group permission values from the database
 *
 * @package Tickit\PermissionBundle\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class GroupPermissionValueRepository extends EntityRepository
{
    /**
     * Gets a collection of all permissions and associated values for a user group
     *
     * @param Group   $group         The user to find group permissions for
     * @param integer $hydrationMode The hydration mode used to hydrate the result
     *
     * @return Collection
     */
    public function findAllForGroupIndexedByName(Group $group, $hydrationMode = Query::HYDRATE_ARRAY)
    {
        $query = $this->getEntityManager()
                      ->createQueryBuilder()
                      ->select('p, gpv')
                      ->from('TickitPermissionBundle:Permission', 'p', 'p.systemName')
                      ->innerJoin('p.groups', 'gpv')
                      ->where('gpv.group = :group_id')
                      ->setParameter('group_id', $group->getId())
                      ->getQuery()
                      ->setHydrationMode($hydrationMode);

        return $query->execute();
    }
}

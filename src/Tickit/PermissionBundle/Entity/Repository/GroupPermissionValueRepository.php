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
     * @param integer $groupId       The user to find group permissions for
     * @param integer $hydrationMode The hydration mode used to hydrate the result
     *
     * @return Collection
     */
    public function findAllForGroupIndexedByName($groupId, $hydrationMode = Query::HYDRATE_ARRAY)
    {
        $query = $this->getEntityManager()
                      ->createQueryBuilder()
                      ->select('p, gpv')
                      ->from('TickitPermissionBundle:Permission', 'p', 'p.systemName')
                      ->innerJoin('p.groups', 'gpv')
                      ->where('gpv.group = :group_id')
                      ->setParameter('group_id', $groupId)
                      ->getQuery()
                      ->setHydrationMode($hydrationMode);

        return $query->execute();
    }

    /**
     * Deletes all GroupPermissionValue entities for the given group.
     *
     * @param Group $group The group to delete permission values for
     *
     * @return void
     */
    public function deleteAllForGroup(Group $group)
    {
        $query = $this->getEntityManager()
                      ->createQuery(
                          'DELETE FROM Tickit\PermissionBundle\Entity\GroupPermissionValue gpv
                              WHERE gpv.group = :group_id'
                      )
                      ->setParameter('group_id', $group->getId());

        $query->execute();
    }
}

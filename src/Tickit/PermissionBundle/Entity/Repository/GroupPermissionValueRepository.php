<?php

namespace Tickit\PermissionBundle\Entity\Repository;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
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
     * @param Group $group The user to find group permissions for
     *
     * @return Collection
     */
    public function findAllForGroup(Group $group)
    {
        $query = $this->getEntityManager()
                      ->createQueryBuilder()
                      ->select('p, gpv')
                      ->from('TickitPermissionBundle:GroupPermissionValue', 'gpv')
                      ->leftJoin('gpv.permission', 'p')
                      ->where('gpv.group = :group_id')
                      ->setParameter('group_id', $group->getId())
                      ->getQuery();

        return $query->execute();
    }
}

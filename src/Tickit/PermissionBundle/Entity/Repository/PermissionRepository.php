<?php

namespace Tickit\PermissionBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Provides methods for retrieving permissions from the database
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class PermissionRepository extends EntityRepository
{

    /**
     * Finds all permissions for a user and their associated groups
     *
     * @param \Tickit\UserBundle\Entity\User $user
     *
     * @return array
     */
    public function findAllForUser(\Tickit\UserBundle\Entity\User $user)
    {
        $groups = $user->getGroups();
        $group = $groups->get(0);

        $permissions = $this->getEntityManager()
                            ->createQuery(
                                'SELECT p
                                    FROM TickitPermissionBundle:Permission p
                                    LEFT JOIN p.users upv
                                    LEFT JOIN p.groups gpv
                                  WHERE (upv.user = :user_id AND upv.value = 1) OR (gpv.group = :group_id AND gpv.value = 1)'
                            )
                            ->setParameter('user_id', $user->getId())
                            ->setParameter('group_id', $group->getId())
                            ->execute();

        return $permissions;
    }

}

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
                                'SELECT p.name, p.system_name, upv.value, gpv.value
                                    FROM \Tickit\PermissionBundle\Entity\Permission p
                                    JOIN \Tickit\PermissionBundle\UserPermissionValue upv
                                    JOIN \Tickit\PermissionBundle\GroupPermissionValue gpv
                                  WHERE upv.user_id = :user_id AND gpv.value = :group_id'
                            )
                            ->setParameter('user_id', $user->getId())
                            ->setParameter('group_id', $group->getId())
                            ->execute();

        //todo... check what permissions output is
        return $permissions;
    }

}

<?php

namespace Tickit\PermissionBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Tickit\UserBundle\Entity\User;

/**
 * Permission repository.
 *
 * Provides methods for retrieving permissions from the database
 *
 * @package Tickit\PermissionBundle\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PermissionRepository extends EntityRepository
{

    /**
     * Finds all permissions for a user and their associated groups
     *
     * @param User $user The user to find permissions for
     *
     * @return array
     */
    public function findAllForUser(User $user)
    {
        $query = $this->getEntityManager()
                            ->createQueryBuilder()
                            ->select('p')
                            ->from('TickitPermissionBundle:Permission', 'p')
                            ->leftJoin('p.users', 'upv')
                            ->where('upv.user = :user_id AND upv.value = 1')
                            ->setParameter('user_id', $user->getId());

        $group = $user->getGroup();
        if (null !== $group) {
            $query->leftJoin('p.groups', 'gpv')
                  ->orWhere('gpv.group = :group_id AND gpv.value = 1')
                  ->setParameter('group_id', $group->getId());
        }

        $permissions = $query->getQuery()->execute();

        return $permissions;
    }
}

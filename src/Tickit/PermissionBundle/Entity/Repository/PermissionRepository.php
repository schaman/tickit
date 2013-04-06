<?php

namespace Tickit\PermissionBundle\Entity\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
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
                            ->innerJoin('p.users', 'u')
                            ->where('u.id = :user_id')
                            ->setParameter('user_id', $user->getId());

        $group = $user->getGroup();
        if (null !== $group) {
            $query->innerJoin('p.groups', 'g')
                  ->orWhere('g.id = :group_id')
                  ->setParameter('group_id', $group->getId());
        }

        $permissions = $query->getQuery()->execute();

        return $permissions;
    }

    /**
     * Gets all permission objects in the data layer, indexed by their primary key
     *
     * @return array
     */
    public function findAllIndexedById()
    {
        $query = $this->getEntityManager()
                      ->createQueryBuilder()
                      ->select('p')
                      ->from('TickitPermissionBundle:Permission', 'p', 'p.id')
                      ->getQuery();

        return $query->execute();
    }
}

<?php

namespace Tickit\PermissionBundle\Entity\Repository;

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
        // TODO: fix this query, it should select all group permissions where no corresponding user permission value exists that is false :)
        $query = $this->getEntityManager()
                      ->createQueryBuilder()
                      ->select('p')
                      ->from('TickitPermissionBundle:Permission', 'p', 'p.id')
                      ->leftJoin('p.users', 'up')
                      ->leftJoin('up.user', 'u')
                      ->innerJoin('p.groups', 'gp')
                      ->innerJoin('gp.group', 'g')
                      ->where('u.id = :user_id')
                      ->orWhere('(g.id = :group_id AND gp.value = :value)')
                      ->setParameter('user_id', $user->getId())
                      ->setParameter('group_id', $user->getGroup()->getId())
                      ->setParameter('value', true)
                      ->getQuery();

        $permissions = $query->execute();

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

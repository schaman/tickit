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

    /**
     * Gets all permissions as an array of key value pairs.
     *
     * The returned array will use IDs as the keys, and the permission name as the value:
     *
     * array(
     *     1 => "Permission Name One",
     *     2 => "Permission Name Two",
     * );
     *
     * @return array
     */
    public function getAllAsKeyValuePairs()
    {
        $query = $this->getEntityManager()
                      ->createQueryBuilder()
                      ->select('p.name')
                      ->from('TickitPermissionBundle:Permission', 'p', 'p.id')
                      ->getQuery();

        $result = $query->getScalarResult();

        $flatPermissions = array();
        foreach ($result as $id => $permission) {
            $flatPermissions[$id] = $permission['name'];
        }

        return $flatPermissions;
    }
}

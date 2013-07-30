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
        $qb = $this->createQueryBuilder('p');
        $query = $qb->innerJoin('p.groups', 'gv')
                    ->leftJoin('p.users', 'uv')
                    ->where(
                        $qb->expr()->orX(
                            $qb->expr()->eq('uv.value', 1),
                            $qb->expr()->andX('gv.value = :value', $qb->expr()->isNull('uv'))
                        )
                    )
                    ->andWhere(
                        $qb->expr()->orX(
                            $qb->expr()->isNull('uv.user'),
                            $qb->expr()->eq('uv.user', ':user_id')
                        )
                    )
                    ->andWhere('gv.group = :group_id')
                    ->setParameter('value', true)
                    ->setParameter('user_id', $user->getId())
                    ->setParameter('group_id', $user->getGroup()->getId())
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

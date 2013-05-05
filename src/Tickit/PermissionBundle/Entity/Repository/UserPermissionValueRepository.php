<?php

namespace Tickit\PermissionBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Tickit\UserBundle\Entity\User;

/**
 * User Permission Value repository.
 *
 * Provides methods for retrieving user permission values from the database
 *
 * @package Tickit\PermissionBundle\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserPermissionValueRepository extends EntityRepository
{
    /**
     * Gets a QueryBuilder that will fetch all permissions and associated values for a user
     *
     * @param User $user The user to find permissions for
     *
     * @return QueryBuilder
     */
    public function findAllForUserQuery(User $user = null)
    {
        $query = $this->getEntityManager()
                      ->createQueryBuilder()
                      ->select('p, upv')
                      ->from('TickitPermissionBundle:UserPermissionValue', 'upv')
                      ->leftJoin('upv.permission', 'p')
                      ->where('upv.user = :user_id')
                      ->setParameter('user_id', $user->getId());

        return $query;
    }
}

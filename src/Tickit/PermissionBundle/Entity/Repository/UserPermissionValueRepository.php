<?php

namespace Tickit\PermissionBundle\Entity\Repository;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
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
     * Gets a collection of all permissions and associated values for a user
     *
     * @param User    $user          The user to find permissions for
     * @param integer $hydrationMode The hydration mode used to hydrate the result
     *
     * @return Collection
     */
    public function findAllForUserIndexedByName(User $user = null, $hydrationMode = Query::HYDRATE_ARRAY)
    {
        $query = $this->getEntityManager()
                      ->createQueryBuilder()
                      ->select('p, upv')
                      ->from('TickitPermissionBundle:Permission', 'p', 'p.systemName')
                      ->innerJoin('p.users', 'upv')
                      ->where('upv.user = :user_id')
                      ->setParameter('user_id', $user->getId())
                      ->getQuery()
                      ->setHydrationMode($hydrationMode);

        return $query->execute();
    }
}

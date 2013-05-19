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
     * @param integer $userId        The user to find permissions for
     * @param string  $indexedBy     The Permission property name to index the results by
     * @param integer $hydrationMode The hydration mode used to hydrate the result
     *
     * @return Collection
     */
    public function findAllForUserIndexedBy($userId, $indexedBy = 'id', $hydrationMode = Query::HYDRATE_ARRAY)
    {
        $query = $this->getEntityManager()
                      ->createQueryBuilder()
                      ->select('p, upv')
                      ->from('TickitPermissionBundle:Permission', 'p', sprintf('p.%s', $indexedBy))
                      ->innerJoin('p.users', 'upv')
                      ->where('upv.user = :user_id')
                      ->setParameter('user_id', $userId)
                      ->getQuery()
                      ->setHydrationMode($hydrationMode);

        return $query->execute();
    }
}

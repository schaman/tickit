<?php

namespace Tickit\PreferenceBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Tickit\UserBundle\Entity\User;

/**
 * Provides a bunch of methods for returning Preference related data from the DBAL
 *
 * @package Tickit\PreferenceBundle\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserPreferenceValueRepository extends EntityRepository
{
    /**
     * Finds preferences for the provided user
     *
     * @param User $user The user to find preferences for
     *
     * @codeCoverageIgnore
     *
     * @return mixed
     */
    public function findAllForUser(User $user)
    {
        return $this->getFindAllForUserQueryBuilder($user)->getQuery()->execute();
    }

    /**
     * Gets a query builder that finds all preferences for a user
     *
     * @param User $user The user to find preferences for
     *
     * @return QueryBuilder
     */
    public function getFindAllForUserQueryBuilder(User $user)
    {
        $queryBuilder = $this->getEntityManager()
                             ->createQueryBuilder()
                             ->select('upv, p')
                             ->from('TickitPreferenceBundle:UserPreferenceValue', 'upv')
                             ->join('upv.preference', 'p')
                             ->where('upv.user = :user_id')
                             ->setParameter('user_id', $user->getId());

        return $queryBuilder;
    }
}

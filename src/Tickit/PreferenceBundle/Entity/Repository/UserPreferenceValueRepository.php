<?php

namespace Tickit\PreferenceBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
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
     * @return mixed
     */
    public function findForUser(User $user)
    {
        $query = $this->getEntityManager()
                            ->createQueryBuilder()
                            ->select('upv')
                            ->from('TickitPreferenceBundle:UserPreferenceValue', 'upv')
                            ->where('upv.user = :user_id')
                            ->setParameter('user_id', $user->getId())
                            ->getQuery();

        return $query->execute();
    }
}

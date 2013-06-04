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
class PreferenceRepository extends EntityRepository
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
        $preferences = $this->getEntityManager()
                            ->createQuery(
                                'SELECT COALESCE(up.value, p.value), p.name, p.system_name, p.id
                                  FROM Tickit\PreferenceBundle\Entity\Preference p
                                   JOIN Tickit\UserBundle\Entity\User u
                                WHERE u.user_id = :user_id'
                            )
                            ->setParameter('user_id', $user->getId())
                            ->execute();

        return $preferences;
    }
}

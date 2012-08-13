<?php

namespace Tickit\PreferenceBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Tickit\UserBundle\Entity\User;

class PreferenceRepository extends EntityRepository
{

    /**
     * Finds preferences for the provided user and groups them logically based on their
     *
     *
     * @param \Tickit\UserBundle\Entity\User $user
     * @return mixed
     */
    public function findForUser(User $user)
    {
        $preferences = $this->getEntityManager()
                            ->createQuery('
                                SELECT COALESCE(up.value, p.value), p.name, p.system_name, p.id
                                  FROM Tickit\PreferenceBundle\Entity\Preference p
                                   JOIN Tickit\UserBundle\Entity\User u
                                WHERE u.user_id = :user_id
                            ')
                            ->setParameter('user_id', $user->getId())
                            ->execute();

        return $preferences;
    }
}
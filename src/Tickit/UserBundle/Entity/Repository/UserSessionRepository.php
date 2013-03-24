<?php

namespace Tickit\UserBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Tickit\UserBundle\Entity\UserSession;

/**
 * Provides methods for retrieving user session related data
 *
 * @package Tickit\UserBundle\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserSessionRepository extends EntityRepository
{
    /**
     * Finds a single UserSession object by its session token
     *
     * @param string $sessionToken The PHP session token to search by
     *
     * @return UserSession
     */
    public function findBySessionToken($sessionToken)
    {
        $userSession = $this->getEntityManager()
                            ->createQuery(
                                'SELECT us.id, us.ip, us.session_token, us.created, us.updated
                                   FROM Tickit\UserBundle\Entity\UserSession us
                                    WHERE us.session_token LIKE :session_token'
                            )
                            ->setParameter('session_token', $sessionToken)
                            ->execute();

        return $userSession;
    }
}

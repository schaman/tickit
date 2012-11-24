<?php

namespace Tickit\UserBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use DateTime;

/**
 * Provides methods for retrieving User related data from the DBAL
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class UserRepository extends EntityRepository
{

    /**
     * Finds a user that has been active in the last X minutes (determined by the $minutes parameter)
     *
     * @param string $sessionToken The session ID of the user to find
     * @param int    $minutes      The number of maximum number of minutes since the last activity
     *
     * @return \Tickit\UserBundle\Entity\User
     */
    public function findActiveUserBySessionToken($sessionToken, $minutes = 15)
    {
        $seconds = $minutes * 60;
        $lastActive = new DateTime(strtotime('Y-m-d H:i:s'));
        $lastActive->modify("-{$seconds} seconds");
        $lastActiveDate = $lastActive->format('Y-m-d H:i:s');

        $user = $this->getEntityManager()
                     ->createQuery(
                         'SELECT u.id, u.username, u.email, u.session_token, u.last_activity FROM Tickit\UserBundle\Entity\User u
                            WHERE u.last_activity >= :last_active
                            AND u.session_token LIKE :session_token'
                     )
                     ->setParameter('last_active', $lastActiveDate)
                     ->setParameter('session_token', $sessionToken)
                     ->execute();

        return $user;
    }

    /**
     * Returns a collection of users that match the given criteria
     *
     * @param array $filters An array of filters used to filter the result
     *
     * @return mixed
     */
    public function findUsers(array $filters = array())
    {
        $usersQ = $this->getEntityManager()
                      ->createQueryBuilder()
                      ->select('u')
                      ->from('TickitUserBundle:User', 'u');

        foreach ($filters as $column => $value) {
            if (is_string($value)) {
                $usersQ->where(sprintf('%s LIKE :%s', $column, $column));
                $usersQ->setParameter($column, $value);
            }
        }

        return $usersQ->getQuery()->execute();
    }

}

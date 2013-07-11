<?php

namespace Tickit\UserBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use DateTime;
use Doctrine\ORM\NoResultException;
use Tickit\CoreBundle\Entity\Repository\FilterableRepositoryInterface;
use Tickit\CoreBundle\Filters\Collection\FilterCollection;
use Tickit\UserBundle\Entity\User;

/**
 * Provides methods for retrieving User related data from the DBAL
 *
 * @package Tickit\UserBundle\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserRepository extends EntityRepository implements FilterableRepositoryInterface
{
    const COLUMN_EMAIL = 'email';
    const COLUMN_USERNAME = 'username';

    /**
     * Finds a user that has been active in the last X minutes (determined by the $minutes parameter)
     *
     * @param string $sessionToken The session ID of the user to find
     * @param int    $minutes      The number of maximum number of minutes since the last activity
     *
     * @return User
     */
    public function findActiveUserBySessionToken($sessionToken, $minutes = 15)
    {
        $seconds = $minutes * 60;
        $lastActive = new DateTime(strtotime('Y-m-d H:i:s'));
        $lastActive->modify("-{$seconds} seconds");
        $lastActiveDate = $lastActive->format('Y-m-d H:i:s');

        $user = $this->getEntityManager()
                     ->createQuery(
                         'SELECT u.id, u.username, u.email, u.session_token, u.last_activity
                              FROM Tickit\UserBundle\Entity\User u
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
                      ->select('u, g')
                      ->from('TickitUserBundle:User', 'u')
                      ->leftJoin('u.group', 'g');

        foreach ($filters as $column => $value) {
            if (is_string($value)) {
                $usersQ->where(sprintf('%s LIKE :%s', $column, $column));
                $usersQ->setParameter($column, $value);
            }
        }

        return $usersQ->getQuery()->execute();
    }

    /**
     * Finds a user by username or email
     *
     * @param string $search The column value to search for
     * @param string $column The column to search on
     *
     * @return mixed
     */
    public function findByUsernameOrEmail($search, $column)
    {
        $usersQ = $this->getEntityManager()
                       ->createQueryBuilder()
                       ->select('u, g')
                       ->from('TickitUserBundle:User', 'u')
                       ->leftJoin('u.group', 'g');

        if ($column == static::COLUMN_USERNAME) {
            $usersQ->where('u.username = :username')
                   ->setParameter('username', $search);
        } else {
            $usersQ->where('u.email = :email')
                   ->setParameter('email', $search);
        }

        try {
            $user = $usersQ->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }

        return $user;
    }

    /**
     * Finds a user by ID.
     *
     * This method will also return associated permissions for the user.
     *
     * @param integer $id The user ID
     *
     * @return User
     */
    public function findById($id)
    {
        $query = $this->getEntityManager()
                      ->createQueryBuilder()
                      ->select('u, g')
                      ->from('TickitUserBundle:User', 'u')
                      ->leftJoin('u.group', 'g')
                      ->where('u.id = :user_id')
                      ->setParameter('user_id', $id)
                      ->getQuery();

        return $query->getSingleResult();
    }

    /**
     * Finds results based off a set of filters.
     *
     * @param FilterCollection $filters The filter collection
     *
     * @return mixed
     */
    public function findByFilters(FilterCollection $filters)
    {
        $query = $this->getEntityManager()
                      ->createQueryBuilder()
                      ->select('u, g')
                      ->from('TickitUserBundle:User', 'u')
                      ->leftJoin('u.group', 'g');

        $filters->applyToQuery($query);

        return $query->getQuery()->execute();
    }
}

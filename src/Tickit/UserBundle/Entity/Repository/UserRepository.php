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
                       ->select('u')
                       ->from('TickitUserBundle:User', 'u');

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
     * @param integer $id The user ID
     *
     * @return User
     */
    public function findById($id)
    {
        $query = $this->getEntityManager()
                      ->createQueryBuilder()
                      ->select('u')
                      ->from('TickitUserBundle:User', 'u')
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
                      ->select('u')
                      ->from('TickitUserBundle:User', 'u');

        $filters->applyToQuery($query);

        return $query->getQuery()->execute();
    }
}

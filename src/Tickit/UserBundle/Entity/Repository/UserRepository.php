<?php

namespace Tickit\UserBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use DateTime;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
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
     * @codeCoverageIgnore
     *
     * @return mixed
     */
    public function findByUsernameOrEmail($search, $column)
    {
        try {
            $user = $this->getFindByUsernameOrEmailQueryBuilder($search, $column)->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }

        return $user;
    }

    /**
     * Gets a query builder that finds a user by username or email
     *
     * @param string $search The column value to search for
     * @param string $column The column to search on
     *
     * @return QueryBuilder
     */
    public function getFindByUsernameOrEmailQueryBuilder($search, $column)
    {
        $queryBuilder = $this->getEntityManager()
                             ->createQueryBuilder()
                             ->select('u')
                             ->from('TickitUserBundle:User', 'u');

        if ($column == static::COLUMN_USERNAME) {
            $queryBuilder->where('u.username = :username')
                         ->setParameter('username', $search);
        } else {
            $queryBuilder->where('u.email = :email')
                         ->setParameter('email', $search);
        }

        return $queryBuilder;
    }

    /**
     * Finds results based off a set of filters.
     *
     * @param FilterCollection $filters The filter collection
     *
     * @codeCoverageIgnore
     *
     * @return mixed
     */
    public function findByFilters(FilterCollection $filters)
    {
        return $this->getFindByFiltersQueryBuilder($filters)->getQuery()->execute();
    }

    /**
     * Gets a query builder that finds a filtered collection of users
     *
     * @param FilterCollection $filters The filter collection
     *
     * @return QueryBuilder
     */
    public function getFindByFiltersQueryBuilder(FilterCollection $filters)
    {
        $queryBuilder = $this->getEntityManager()
                             ->createQueryBuilder()
                             ->select('u')
                             ->from('TickitUserBundle:User', 'u');

        $filters->applyToQuery($queryBuilder);

        return $queryBuilder;
    }
}

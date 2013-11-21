<?php

/*
 * Tickit, an open source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tickit\Bundle\UserBundle\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Tickit\Component\Entity\Repository\UserRepositoryInterface;
use Tickit\Component\Filter\Repository\FilterableRepositoryInterface;
use Tickit\Component\Filter\Collection\FilterCollection;
use Tickit\Component\Model\User\User;

/**
 * Provides methods for retrieving User related data from the DBAL
 *
 * @package Tickit\Bundle\UserBundle\Doctrine\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserRepository extends EntityRepository implements UserRepositoryInterface, FilterableRepositoryInterface
{
    /**
     * Finds a user by username or email
     *
     * @param string $search The column value to search for
     * @param string $column The column to search on
     *
     * @codeCoverageIgnore
     *
     * @return User
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

    /**
     * Finds a user by confirmation token.
     *
     * @param string $token The confirmation token to find the user by
     *
     * @codeCoverageIgnore
     *
     * @return User
     */
    public function findByConfirmationToken($token)
    {
        return $this->findBy(['confirmationToken' => $token]);
    }
}

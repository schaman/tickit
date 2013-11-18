<?php

namespace Tickit\Component\Entity\Repository;

use Tickit\Component\Model\User\User;

/**
 * User repository interface
 *
 * User repositories are responsible for fetching user objects
 * from the data layer.
 *
 * @package Tickit\Component\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface UserRepositoryInterface
{
    const COLUMN_EMAIL = 'email';
    const COLUMN_USERNAME = 'username';

    /**
     * Finds a user by their unique identifier
     *
     * @param integer $id The identifier
     *
     * @return User
     */
    public function find($id);

    /**
     * Finds a user by username or email
     *
     * @param string $search The column value to search for
     * @param string $column The column to search on
     *
     * @return User
     */
    public function findByUsernameOrEmail($search, $column);

    /**
     * Finds a user that matches the given criteria
     *
     * @param array $criteria The criteria to find the user by (column => value pairs)
     *
     * @return User
     */
    public function findOneBy(array $criteria);
}

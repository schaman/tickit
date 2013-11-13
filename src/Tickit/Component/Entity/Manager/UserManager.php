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

namespace Tickit\Component\Entity\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Tickit\Bundle\UserBundle\Doctrine\Repository\UserRepository;
use Tickit\Bundle\UserBundle\Entity\User;
use Tickit\Component\Event\Dispatcher\AbstractEntityEventDispatcher;

/**
 * User entity manager.
 *
 * Provides functionality for managing User objects in the application.
 *
 * Because the user bundle relies on the use of FOSUserBundle we need to use the
 * FOS user manager where possible to ensure correct functionality.
 *
 * @package Tickit\Component\Entity\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserManager extends AbstractManager implements UserManagerInterface
{
    /**
     * The FOS user manager
     *
     * @var UserManagerInterface
     */
    protected $fosManager;

    /**
     * The user repository
     *
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * Constructor.
     *
     * @param UserRepository                $userRepository The user repository
     * @param EntityManagerInterface        $em             An entity manager
     * @param AbstractEntityEventDispatcher $dispatcher     The event dispatcher
     * @param UserManagerInterface          $fosManager     The FOS user manager
     */
    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $em,
        AbstractEntityEventDispatcher $dispatcher,
        UserManagerInterface $fosManager
    ) {
        parent::__construct($em, $dispatcher);

        $this->userRepository = $userRepository;
        $this->fosManager = $fosManager;
    }

    /**
     * {@inheritDoc}
     *
     * @return UserRepository
     */
    public function getRepository()
    {
        return $this->userRepository;
    }

    /**
     * {@inheritDoc}
     *
     * @param object  $entity The user object to create in the entity manager
     * @param boolean $flush  True to flush changes in the entity manager
     *
     * @return User
     */
    public function create($entity, $flush = true)
    {
        $this->fosManager->updateCanonicalFields($entity);
        $this->fosManager->updatePassword($entity);

        return parent::create($entity, $flush);
    }

    /**
     * {@inheritDoc}
     *
     * @param object  $entity The user object to update in the entity manager
     * @param boolean $flush  True to flush changes in the entity manager
     *
     * @return User
     */
    public function update($entity, $flush = true)
    {
        $this->updateCanonicalFields($entity);
        $this->updatePassword($entity);

        return parent::update($entity, $flush);
    }

    /**
     * Creates an empty user instance.
     *
     * @return UserInterface
     */
    public function createUser()
    {
        return new User();
    }

    /**
     * Deletes a user.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function deleteUser(UserInterface $user)
    {
        $this->delete($user);
    }

    /**
     * Finds one user by the given criteria.
     *
     * @param array $criteria
     *
     * @return UserInterface
     */
    public function findUserBy(array $criteria)
    {
        return $this->getRepository()->findOneBy($criteria);
    }

    /**
     * Find a user by its username.
     *
     * @param string $username
     *
     * @return UserInterface or null if user does not exist
     */
    public function findUserByUsername($username)
    {
        return $this->getRepository()->findByUsernameOrEmail($username, UserRepository::COLUMN_USERNAME);
    }

    /**
     * Finds a user by its email.
     *
     * @param string $email
     *
     * @return UserInterface or null if user does not exist
     */
    public function findUserByEmail($email)
    {
        return $this->getRepository()->findByUsernameOrEmail($email, UserRepository::COLUMN_EMAIL);
    }

    /**
     * Finds a user by its username or email.
     *
     * @param string $usernameOrEmail
     *
     * @return UserInterface or null if user does not exist
     */
    public function findUserByUsernameOrEmail($usernameOrEmail)
    {
        if (filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
            return $this->findUserByEmail($usernameOrEmail);
        }

        return $this->findUserByUsername($usernameOrEmail);
    }

    /**
     * Finds a user by its confirmationToken.
     *
     * @param string $token
     *
     * @return UserInterface or null if user does not exist
     */
    public function findUserByConfirmationToken($token)
    {
        return $this->findUserBy(array('confirmationToken' => $token));
    }

    /**
     * Returns a collection with all user instances.
     *
     * @return \Traversable
     */
    public function findUsers()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * Returns the user's fully qualified class name.
     *
     * @return string
     */
    public function getClass()
    {
        return 'Tickit\Bundle\UserBundle\Entity\User';
    }

    /**
     * Reloads a user.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function reloadUser(UserInterface $user)
    {
        $this->em->refresh($user);
    }

    /**
     * Updates a user.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function updateUser(UserInterface $user)
    {
        $this->update($user);
    }

    /**
     * Updates the canonical username and email fields for a user.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function updateCanonicalFields(UserInterface $user)
    {
        $this->fosManager->updateCanonicalFields($user);
    }

    /**
     * Updates a user password if a plain password is set.
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function updatePassword(UserInterface $user)
    {
        $this->fosManager->updatePassword($user);
    }

    /**
     * Finds a user by ID
     *
     * @param integer $id The user ID to find by
     *
     * @return User
     */
    public function find($id)
    {
        try {
            return $this->getRepository()->find($id);
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * {@inheritDoc}
     *
     * @param object $entity The entity in its current state
     *
     * @return User
     */
    protected function fetchEntityInOriginalState($entity)
    {
        $user = $this->getRepository()->find($entity->getId());

        return $user;
    }
}

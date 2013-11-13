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

namespace Tickit\Component\Security;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Tickit\Component\Model\User\User;
use Tickit\Component\Entity\Manager\UserManager;

/**
 * User provider implementation.
 *
 * Provides functionality for providing user information to Symfony's
 * security component
 *
 * @package Tickit\Component\Security
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserProvider implements UserProviderInterface
{
    /**
     * The user manager
     *
     * @var UserManager
     */
    protected $manager;

    /**
     * Constructor.
     *
     * @param UserManager $manager The user manager
     */
    public function __construct(UserManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Loads the user for the given username.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException If the user is not found
     *
     */
    public function loadUserByUsername($username)
    {
        $user = $this->manager->findUserByUsernameOrEmail($username);

        if (!$user) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        return $user;
    }

    /**
     * Refreshes the user for the account interface.
     *
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException  If the account is not supported
     * @throws UsernameNotFoundException If the username does not exist
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Expected an instance of Tickit\Component\Model\User\User, but got "%s".', get_class($user))
            );
        }

        $reloadedUser = $this->manager->findUserBy(array('id' => $user->getId()));
        if (null === $reloadedUser) {
            throw new UsernameNotFoundException(sprintf('User with ID "%d" could not be reloaded.', $user->getId()));
        }

        return $reloadedUser;
    }

    /**
     * Whether this provider supports the given user class
     *
     * @param string $class
     *
     * @return boolean
     */
    public function supportsClass($class)
    {
        $userClass = $this->manager->getClass();

        return $userClass === $class || is_subclass_of($class, $userClass);
    }
}

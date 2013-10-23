<?php

namespace Tickit\UserBundle\Tests\Security\Mock;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Mock invalid user type that implements UserInterface
 *
 * @package Tickit\UserBundle\Tests\Security\Mock
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class MockInvalidTypeUser implements UserInterface
{
    /**
     * Returns the roles granted to the user.
     *
     * @return void
     */
    public function getRoles()
    {
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * @return void
     */
    public function getPassword()
    {
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return void
     */
    public function getSalt()
    {
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     *
     * @return void
     */
    public function eraseCredentials()
    {
    }
}

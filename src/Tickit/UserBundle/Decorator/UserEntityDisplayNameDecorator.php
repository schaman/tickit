<?php

namespace Tickit\UserBundle\Decorator;

use Tickit\UserBundle\Entity\User;

/**
 * Decorate a user entity into display name
 *
 * @package Tickit\UserBundle\Decorator
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class UserEntityDisplayNameDecorator
{
    /**
     * Decorate a user entity into display name
     *
     * @param User $user
     * @return string
     */
    public function decorate(User $user)
    {
        return $user->getFullName();
    }
}

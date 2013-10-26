<?php

namespace Tickit\UserBundle\Decorator;

use Tickit\CoreBundle\Form\Type\Picker\EntityDecoratorInterface;
use Tickit\UserBundle\Entity\User;

/**
 * Decorate a user entity into display name
 *
 * @package Tickit\UserBundle\Decorator
 * @author  Mark Wilson <mark@89allport.co.uk>
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserEntityDisplayNameDecorator implements EntityDecoratorInterface
{
    /**
     * Decorate a user entity into display name
     *
     * @param User $user The user to decorate
     *
     * @throws \InvalidArgumentException If the $user is not a valid User instance
     *
     * @return string
     */
    public function decorate($user)
    {
        if (!$user instanceof User) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The user must be an instance of Tickit\UserBundle\Entity\User, %s given',
                    gettype($user)
                )
            );
        }

        return $user->getFullName();
    }
}

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

namespace Tickit\Component\Decorator\Entity;

use Tickit\Component\Decorator\Entity\EntityDecoratorInterface;
use Tickit\Component\Model\User\User;

/**
 * Decorate a user entity into display name
 *
 * @package Tickit\Component\Decorator\Entity
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
                    'The user must be an instance of Tickit\Component\Model\User\User, %s given',
                    gettype($user)
                )
            );
        }

        return $user->getFullName();
    }
}

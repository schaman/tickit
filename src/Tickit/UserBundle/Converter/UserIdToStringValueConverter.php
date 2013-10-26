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

namespace Tickit\UserBundle\Converter;

use Doctrine\ORM\EntityNotFoundException;
use Tickit\CoreBundle\Form\Type\Picker\EntityConverterInterface;
use Tickit\UserBundle\Decorator\UserEntityDisplayNameDecorator;
use Tickit\UserBundle\Manager\UserManager;

/**
 * Convert user entity values
 *
 * @package Tickit\UserBundle\Decorator
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class UserIdToStringValueConverter implements EntityConverterInterface
{
    /**
     * User manager
     *
     * @var \Tickit\UserBundle\Manager\UserManager
     */
    private $manager;

    /**
     * The user decorator
     *
     * @var UserEntityDisplayNameDecorator
     */
    private $decorator;

    /**
     * Constructor.
     *
     * @param UserManager                    $manager       The user manager
     * @param UserEntityDisplayNameDecorator $userDecorator The user decorator
     */
    public function __construct(UserManager $manager, UserEntityDisplayNameDecorator $userDecorator)
    {
        $this->manager = $manager;
        $this->decorator = $userDecorator;
    }

    /**
     * Convert user Id to a display name
     *
     * @param integer $userId User Id
     *
     * @return string
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function convert($userId)
    {
        $user = $this->manager->find($userId);
        if (!$user) {
            throw new EntityNotFoundException(sprintf('User not found (%d)', $userId));
        }

        return $this->decorator->decorate($user);
    }
}

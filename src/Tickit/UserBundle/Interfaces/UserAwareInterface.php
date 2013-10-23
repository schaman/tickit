<?php

/*
 * Tickit, an source web based bug management tool.
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

namespace Tickit\UserBundle\Interfaces;

use Tickit\UserBundle\Entity\User;

/**
 * Interface for classes that are User aware
 *
 * @package Tickit\UserBundle\Interfaces
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @see     Tickit\UserBundle\Entity\User
 */
interface UserAwareInterface
{
    /**
     * Gets the user on this object
     *
     * @return User
     */
    public function getUser();

    /**
     * Sets the user on this object
     *
     * @param User $user The user to set
     *
     * @return mixed
     */
    public function setUser(User $user);
}

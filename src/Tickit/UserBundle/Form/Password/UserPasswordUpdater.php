<?php

/*
 * 
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
 * 
 */

namespace Tickit\UserBundle\Form\Password;

use Tickit\UserBundle\Entity\User;

/**
 * User password updater.
 *
 * Responsible for handling a user's password between states (original user and new user)
 *
 * @package Tickit\UserBundle\Password
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserPasswordUpdater
{
    /**
     * Updates a user's plain password
     *
     * @param User $originalUserState The original state of the user
     * @param User $userBeingUpdated  The state of the user after updates
     *
     * @return User The state of the user after the password updates have been resolved
     */
    public function updatePassword(User $originalUserState, User $userBeingUpdated)
    {
        // we restore the password if no new one was provided so that the user's password
        // isn't set to a blank string in the database (user has to have a password set)
        if ($userBeingUpdated->getPassword() === null) {
            $userBeingUpdated->setPassword($originalUserState->getPassword());
        } else {
            // set the plain password on the user from the one that was provided (assuming it is a plain password
            // set from a form submission)
            $userBeingUpdated->setPlainPassword($userBeingUpdated->getPassword());
        }

        return $userBeingUpdated;
    }
}

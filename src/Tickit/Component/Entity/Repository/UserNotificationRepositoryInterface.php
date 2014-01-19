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

namespace Tickit\Component\Entity\Repository;

use Tickit\Component\Model\User\User;

/**
 * User Notification repository interface.
 *
 * User Notification repositories are responsible for fetching user
 * notification objects from the data layer.
 *
 * @package Tickit\Component\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface UserNotificationRepositoryInterface
{
    /**
     * Finds unread user notifications
     *
     * @param User      $user  The user to find unread notifications for
     * @param \DateTime $since An optional DateTime instance used to only return notifications
     *                         created since a given point in time
     *
     * @return array
     */
    public function findUnreadForUser(User $user, \DateTime $since = null);
}

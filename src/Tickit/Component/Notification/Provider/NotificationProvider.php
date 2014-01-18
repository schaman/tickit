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

namespace Tickit\Component\Notification\Provider;

use Tickit\Component\Entity\Repository\UserNotificationRepositoryInterface;
use Tickit\Component\Model\User\User;

/**
 * Notification provider.
 *
 * Responsible for providing notification data in the application.
 *
 * @package Tickit\Component\Notification\Provider
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NotificationProvider
{
    /**
     * The user notification repository
     *
     * @var UserNotificationRepositoryInterface
     */
    protected $userNotificationRepo;

    /**
     * The maximum number of notification messages to return.
     *
     * @var integer
     */
    protected $messageLimit;

    /**
     * Constructor.
     *
     * @param UserNotificationRepositoryInterface $userNotificationRepo The user notification repository
     * @param integer                             $messageLimit         The maximum number of notification messages
     *                                                                  to return
     */
    public function __construct(UserNotificationRepositoryInterface $userNotificationRepo, $messageLimit)
    {
        $this->userNotificationRepo = $userNotificationRepo;
        $this->messageLimit = $messageLimit;
    }

    /**
     * Finds unread notifications for a given user
     *
     * @param User      $user  The user to find notifications for
     * @param \DateTime $since An optional DateTime instance used to only return notifications
     *                         created since a given point in time
     *
     * @return array
     */
    public function findUnreadForUser(User $user, \DateTime $since = null)
    {
        return $this->userNotificationRepo->findUnreadForUser($user, $since);
    }

    /**
     * Gets the user notification repository
     *
     * @return UserNotificationRepositoryInterface
     */
    public function getUserNotificationRepository()
    {
        return $this->userNotificationRepo;
    }
}

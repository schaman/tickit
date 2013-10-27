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

namespace Tickit\Bundle\NotificationBundle\Factory;

use Tickit\Bundle\NotificationBundle\Entity\UserNotification;
use Tickit\Bundle\NotificationBundle\Model\NotificationDataInterface;
use Tickit\UserBundle\Entity\User;

/**
 * Notification factory.
 *
 * Responsible for creating new notifications in the data layer, and triggering
 * a push to the client.
 *
 * @package Tickit\Bundle\NotificationBundle\Factory
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NotificationFactory
{
    /**
     * Creates a new user notification from a message object.
     *
     * @param NotificationDataInterface $message The user notification message
     * @param User                      $user    The user that is being notified
     *
     * @throws \InvalidArgumentException If the notification message is empty
     *
     * @return UserNotification
     */
    public function notifyUser(NotificationDataInterface $message, User $user)
    {
        $messageBody = $message->getMessage();
        if (empty($messageBody)) {
            throw new \InvalidArgumentException('You must provide a notification message');
        }

        $notification = new UserNotification();
        $notification->setRecipient($user)
                     ->setMessage($message->getMessage());

        return $notification;
    }
}

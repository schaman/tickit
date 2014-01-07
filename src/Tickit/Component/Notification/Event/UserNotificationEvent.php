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

namespace Tickit\Component\Notification\Event;

use Symfony\Component\EventDispatcher\Event;
use Tickit\Component\Model\User\User;
use Tickit\Component\Notification\Model\UserNotification;

/**
 * User notification event.
 *
 * An event object dispatched whenever a user receives a new
 * notification.
 *
 * @package Tickit\Component\Notification\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserNotificationEvent extends Event
{
    /**
     * The notification
     *
     * @var UserNotification
     */
    private $notification;

    /**
     * Constructor.
     *
     * @param UserNotification $notification The notification dispatched
     */
    public function __construct(UserNotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Gets the recipient user of the notification
     *
     * @return User
     */
    public function getUser()
    {
        return $this->getNotification()->getRecipient();
    }

    /**
     * Gets the notification on the event
     *
     * @return UserNotification
     */
    public function getNotification()
    {
        return $this->notification;
    }
}

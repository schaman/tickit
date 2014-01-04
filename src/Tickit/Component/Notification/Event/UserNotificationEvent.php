<?php

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

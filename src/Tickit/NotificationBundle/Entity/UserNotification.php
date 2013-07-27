<?php

namespace Tickit\NotificationBundle\Entity;

/**
 * User notification.
 *
 * A user notification is specific to an individual user.
 *
 * @package Tickit\NotificationBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserNotification
{
    protected $recipient;

    protected $readAt;
}

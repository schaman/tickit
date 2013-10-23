<?php

namespace Tickit\NotificationBundle\Model;

/**
 * Notification data interface.
 *
 * Used to inject data into a notification object, these will change between notification types.
 *
 * @package Tickit\NotificationBundle\Model
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface NotificationDataInterface
{
    /**
     * Gets the notification message body.
     *
     * @return string
     */
    public function getMessage();
}

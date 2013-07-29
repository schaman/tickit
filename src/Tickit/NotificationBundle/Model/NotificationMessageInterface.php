<?php

namespace Tickit\NotificationBundle\Model;

/**
 * Notification message interface.
 *
 * Messages are used to inject content into a notification object, and
 * they will change between notification types.
 *
 * @package Tickit\NotificationBundle\Model
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface NotificationMessageInterface
{
    /**
     * Gets the notification message body.
     *
     * @return string
     */
    public function getMessage();
}

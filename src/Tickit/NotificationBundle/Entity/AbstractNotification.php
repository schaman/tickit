<?php

namespace Tickit\NotificationBundle\Entity;



/**
 * Abstract notification entity.
 *
 * Provides base data for notifications.
 *
 * @package Tickit\NotificationBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AbstractNotification
{
    protected $id;

    protected $message;

    protected $createdAt;
}

<?php

namespace Tickit\NotificationBundle\Entity;

/**
 * Represents a read action on a group notification for an individual user.
 *
 * The purpose for this entity is to track whether a user has read a group
 * notification that they have received, so they do not receive it again.
 *
 * @package Tickit\NotificationBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class GroupNotificationReadStatus
{
    protected $id;

    protected $user;

    protected $readAt;
}

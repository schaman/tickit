<?php

namespace Tickit\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tickit\UserBundle\Entity\User;

/**
 * Represents a read action on a group notification for an individual user.
 *
 * The purpose for this entity is to track whether a user has read a group
 * notification that they have received, so they do not receive it again.
 *
 * @package Tickit\NotificationBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @ORM\Entity
 * @ORM\Table(name="group_notification_user_read_status")
 */
class GroupNotificationUserReadStatus
{
    /**
     * The group notification
     *
     * @var GroupNotification
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Tickit\NotificationBundle\Entity\GroupNotification", inversedBy="readStatuses")
     * @ORM\JoinColumn(name="notification_id", referencedColumnName="id")
     */
    protected $notification;

    /**
     * The user that this read status is for
     *
     * @var User
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Tickit\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * The date and time that the group notification was read by the user
     *
     * @var \DateTime
     * @ORM\Column(type="datetime", name="read_at")
     */
    protected $readAt;

    /**
     * Sets the notification
     *
     * @param GroupNotification $notification The group notification
     *
     * @return GroupNotificationUserReadStatus
     */
    public function setNotification($notification)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * Gets the group notification that the read status is for
     *
     * @return GroupNotification
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * Sets the datetime that the notification was read
     *
     * @param \DateTime $readAt The datetime
     *
     * @return GroupNotificationUserReadStatus
     */
    public function setReadAt($readAt)
    {
        $this->readAt = $readAt;

        return $this;
    }

    /**
     * Gets the datetime that the notification was read
     *
     * @return \DateTime
     */
    public function getReadAt()
    {
        return $this->readAt;
    }

    /**
     * Sets the user that this read status is for
     *
     * @param User $user The user
     *
     * @return GroupNotificationUserReadStatus
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Gets the user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}

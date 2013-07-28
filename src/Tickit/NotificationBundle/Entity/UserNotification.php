<?php

namespace Tickit\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tickit\UserBundle\Entity\User;

/**
 * User notification.
 *
 * A user notification is specific to an individual user.
 *
 * @package Tickit\NotificationBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @ORM\Entity
 * @ORM\Table(name="user_notifications")
 */
class UserNotification extends AbstractNotification
{
    /**
     * The recipient user
     *
     * @var User
     * @ORM\ManyToOne(targetEntity="Tickit\UserBundle\Entity\User", inversedBy="notifications")
     * @ORM\JoinColumn(name="recipient_id", referencedColumnName="id")
     */
    protected $recipient;

    /**
     * The date and time that this notification was read
     *
     * @var \DateTime
     * @ORM\Column(type="datetime", name="read_at", nullable=true)
     */
    protected $readAt;

    /**
     * Sets the read datetime
     *
     * @param \DateTime $readAt The datetime that this notification was read
     *
     * @return UserNotification
     */
    public function setReadAt($readAt)
    {
        $this->readAt = $readAt;

        return $this;
    }

    /**
     * Gets the read datetime
     *
     * @return \DateTime
     */
    public function getReadAt()
    {
        return $this->readAt;
    }

    /**
     * Sets the recipient user for this notification
     *
     * @param User $recipient The recipient user
     *
     * @return UserNotification
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * Gets the recipient user
     *
     * @return User
     */
    public function getRecipient()
    {
        return $this->recipient;
    }
}

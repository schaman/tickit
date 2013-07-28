<?php

namespace Tickit\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tickit\UserBundle\Entity\Group;

/**
 * Group notification.
 *
 * @package Tickit\NotificationBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @ORM\Entity
 * @ORM\Table(name="group_notifications")
 */
class GroupNotification extends AbstractNotification
{
    /**
     * The recipient group for this notification
     *
     * @var Group
     * @ORM\ManyToOne(targetEntity="Tickit\UserBundle\Entity\Group", inversedBy="notifications")
     * @ORM\JoinColumn(name="recipient_id", referencedColumnName="id")
     */
    protected $recipient;

    /**
     * Sets the recipient group for this notifications
     *
     * @param Group $recipient The recipient group
     *
     * @return GroupNotification
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * Gets the recipient group
     *
     * @return Group
     */
    public function getRecipient()
    {
        return $this->recipient;
    }
}

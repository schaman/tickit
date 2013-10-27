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

namespace Tickit\Bundle\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tickit\UserBundle\Entity\User;

/**
 * User notification.
 *
 * A user notification is specific to an individual user.
 *
 * @package Tickit\Bundle\NotificationBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @ORM\Entity(repositoryClass="Tickit\Bundle\NotificationBundle\Entity\Repository\UserNotificationRepository")
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

<?php

/*
 * Tickit, an source web based bug management tool.
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

namespace Tickit\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

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
    /**
     * Unique identifier
     *
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="bigint")
     */
    protected $id;

    /**
     * The message body of the notification
     *
     * @var string
     * @ORM\Column(type="string", length=220)
     */
    protected $message;

    /**
     * The time that this notification was created
     *
     * @var \DateTime
     * @ORM\Column(type="datetime", name="created_at")
     * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt;

    /**
     * The action URI for the notification (if any)
     *
     * @var string
     * @ORM\Column(type="string", name="action_uri")
     */
    protected $actionUri;

    /**
     * Sets the created time
     *
     * @param \DateTime $createdAt The datetime that this notification was created
     *
     * @return AbstractNotification
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Gets the created time
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Gets the identifier
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the message content
     *
     * @param string $message The message
     *
     * @return AbstractNotification
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Gets the message content
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets the action URI for the notification
     *
     * @param string $actionUri The action URI
     *
     * @return AbstractNotification
     */
    public function setActionUri($actionUri)
    {
        $this->actionUri = $actionUri;

        return $this;
    }

    /**
     * Gets the action URI for the notification
     *
     * @return string
     */
    public function getActionUri()
    {
        return $this->actionUri;
    }
}

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

namespace Tickit\Component\Model\Client;

use Doctrine\Common\Collections\Collection;

/**
 * Client entity.
 *
 * @package Tickit\Component\Model\Client
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class Client
{
    const STATUS_ACTIVE = 'active';
    const STATUS_ARCHIVED = 'archived';

    /**
     * The unique identifier for the client
     *
     * @var integer
     */
    private $id;

    /**
     * The name of the client
     *
     * @var string
     */
    private $name;

    /**
     * The client url.
     *
     * This is usually the homepage for the client.
     *
     * @var string
     */
    private $url;

    /**
     * Additional notes for the client
     *
     * @var string
     */
    private $notes;

    /**
     * The status of the client
     *
     * @var string
     */
    private $status;

    /**
     * The total number of projects that this client owns.
     *
     * This is stored as an object property for performance reasons so a full
     * join with the Project entity is not required.
     *
     * @var integer
     */
    private $totalProjects;

    /**
     * The date and time the client was created
     *
     * @var \DateTime
     */
    private $createdAt;

    /**
     * The date and time the client was last updated
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * Projects that belong to this client
     *
     * @var Collection
     */
    private $projects;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->setStatus(static::STATUS_ACTIVE);
        $this->totalProjects = 0;
    }

    /**
     * Returns an array of valid status types
     *
     * @param boolean $withFriendlyNames If true, will return an array of user-friendly status values
     *                                   indexed by their value
     *
     * @return array
     */
    public static function getValidStatuses($withFriendlyNames = false)
    {
        if (true === $withFriendlyNames) {
            return [
                static::STATUS_ACTIVE => 'Active',
                static::STATUS_ARCHIVED => 'Archived'
            ];
        }

        return [static::STATUS_ACTIVE, static::STATUS_ARCHIVED];
    }

    /**
     * Returns false if the given status is not valid
     *
     * @param string $status The status to check
     *
     * @return boolean
     */
    public static function checkStatusIsValid($status)
    {
        return in_array($status, static::getValidStatuses());
    }

    /**
     * Get the unique identifier
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the unique identifier
     *
     * @param integer $id The unique identifier
     *
     * @return Client
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Sets the client name
     *
     * @param string $name The new client name
     *
     * @return Client
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the client name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the client URL
     *
     * @param string $url The new client URL
     *
     * @return Client
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets client notes
     *
     * @param string $notes Client notes
     *
     * @return Client
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Gets client notes
     *
     * @return string 
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Sets the date and time that the client was created
     *
     * @param \DateTime $createdAt The date time
     *
     * @return Client
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Gets the date and time that the client was created
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Sets the date and time that the client was updated
     *
     * @param \DateTime $updatedAt The date time
     *
     * @return Client
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Gets the date and time that this client was updated
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Sets the status for the client
     *
     * @param string $status The new client status
     *
     * @throws \InvalidArgumentException If an invalid status is provided
     *
     * @return Client
     */
    public function setStatus($status)
    {
        if (false === static::checkStatusIsValid($status)) {
            throw new \InvalidArgumentException(
                sprintf('An invalid status was provided (%s)', $status)
            );
        }

        $this->status = $status;

        return $this;
    }

    /**
     * Gets the status of the client
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Increments the total project count and returns it
     *
     * @return integer
     */
    public function incrementTotalProjects()
    {
        return ++$this->totalProjects;
    }

    /**
     * Decrements the total project count and returns it
     *
     * @return integer
     */
    public function decrementTotalProjects()
    {
        // the total project count cannot be less than 0
        if (0 === $this->totalProjects) {
            return 0;
        }

        return --$this->totalProjects;
    }

    /**
     * Gets the total project count
     *
     * @return integer
     */
    public function getTotalProjects()
    {
        return $this->totalProjects;
    }
}

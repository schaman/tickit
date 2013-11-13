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

namespace Tickit\Bundle\ClientBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Client entity.
 *
 * @package Tickit\Bundle\ClientBundle\Entity
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
    private $created;

    /**
     * The date and time the client was last updated
     *
     * @var \DateTime
     */
    private $updated;

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
     * @param \DateTime $created The date time
     *
     * @return Client
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Gets the date and time that the client was created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Sets the date and time that the client was updated
     *
     * @param \DateTime $updated The date time
     *
     * @return Client
     */
    public function setUpdated(\DateTime $updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Gets the date and time that this client was updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Sets the status for the client
     *
     * @param string $status The new client status
     *
     * @throws \InvalidArgumentException If an invalid status is provided
     */
    public function setStatus($status)
    {
        if (!in_array($status, [static::STATUS_ACTIVE, static::STATUS_ARCHIVED])) {
            throw new \InvalidArgumentException(
                sprintf('An invalid status was provided (%s)', $status)
            );
        }

        $this->status = $status;
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

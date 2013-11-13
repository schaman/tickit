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

namespace Tickit\Bundle\ProjectBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DateTime;
use Tickit\Component\Model\Client\Client;

/**
 * Project entity
 *
 * Represents an application/website/product within the application
 */
class Project
{
    /**
     * The unique identifier for this project
     *
     * @var integer
     */
    protected $id;

    /**
     * The name of this project
     *
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * The client that this project relates to
     *
     * @var Client
     */
    protected $client;

    /**
     * Tickets related to this project
     *
     * @var Collection
     */
    protected $tickets;

    /**
     * Attribute values for this project
     *
     * @var Collection
     */
    protected $attributes;

    /**
     * The date/time that this project was created
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * The date/time that this project was last updated
     *
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * The time that this project was deleted (if any)
     *
     * @var string
     */
    protected $deletedAt;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->attributes = new ArrayCollection();
    }

    /**
     * Gets the project ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the project name
     *
     * @param string $name
     *
     * @return Project
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the project name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the created time as an instance of DateTime
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Gets the updated time as an instance of DateTime
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Gets the time at which this project was deleted
     *
     * @return DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Sets attributes on this project
     *
     * @param Collection $attributes The new collection of attributes
     *
     * @return Project
     */
    public function setAttributes(Collection $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Gets the attributes for this project
     *
     * @return Collection
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Sets the time this project was deleted
     *
     * @param DateTime $deletedAt The date time that this project was deleted
     *
     * @return Project
     */
    public function setDeletedAt(DateTime $deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * __toString() method
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Set tickets on this project
     *
     * @param Collection $tickets The tickets collection
     *
     * @return Project
     */
    public function setTickets(Collection $tickets)
    {
        $this->tickets = $tickets;

        return $this;
    }

    /**
     * Sets the updated time
     *
     * @param \DateTime $updatedAt The updated time
     *
     * @return Project
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Sets the created time
     *
     * @param \DateTime $createdAt The created time
     *
     * @return Project
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Sets the project's Client
     *
     * @param Client $client The client
     *
     * @return Project
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Gets the client for this project
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }
}

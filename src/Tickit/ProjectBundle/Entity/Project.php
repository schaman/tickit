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

namespace Tickit\ProjectBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use DateTime;

/**
 * Project entity
 *
 * Represents an application/website/product within the application
 *
 * @ORM\Entity(repositoryClass="Tickit\ProjectBundle\Entity\Repository\ProjectRepository")
 * @ORM\Table(name="projects")
 */
class Project
{
    /**
     * The unique identifier for this project
     *
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
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
     * Tickets related to this project
     *
     * @var Collection
     * @ORM\OneToMany(targetEntity="Tickit\TicketBundle\Entity\Ticket", mappedBy="project")
     */
    protected $tickets;

    /**
     * Attribute values for this project
     *
     * @var Collection
     * @ORM\OneToMany(targetEntity="AbstractAttributeValue", mappedBy="project", cascade="persist")
     */
    protected $attributes;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    protected $updated;

    /**
     * The time that this project was deleted (if any)
     *
     * @var string
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
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
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Gets the updated time as an instance of DateTime
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
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
     * @param \DateTime $updated The updated time
     *
     * @return Project
     */
    public function setUpdated(\DateTime $updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Sets the created time
     *
     * @param \DateTime $created The created time
     *
     * @return Project
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;

        return $this;
    }
}

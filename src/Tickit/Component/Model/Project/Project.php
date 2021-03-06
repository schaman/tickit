<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
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

namespace Tickit\Component\Model\Project;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Tickit\Component\Model\IdentifiableInterface;
use Tickit\Component\Model\Client\Client;
use Tickit\Component\Model\User\User;

/**
 * Project entity
 *
 * Represents an application/website/product within the application
 *
 * @package Tickit\Component\Model\Project
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class Project implements IdentifiableInterface
{
    const STATUS_ACTIVE = 'active';
    const STATUS_ARCHIVED = 'archived';

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
     */
    protected $name;

    /**
     * The client that this project relates to
     *
     * @var Client
     */
    protected $client;

    /**
     * The prefix for issues on this project
     *
     * @var string
     */
    protected $issuePrefix;

    /**
     * The status of this project
     *
     * @var string
     */
    protected $status;

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
     * The user who owns this project
     *
     * @var User
     */
    protected $owner;

    /**
     * Issues related to this project
     *
     * @var Collection
     */
    protected $issues;

    /**
     * Attribute values for this project
     *
     * @var Collection
     */
    protected $attributes;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->attributes = new ArrayCollection();
        $this->issues = new ArrayCollection();
        $this->status = static::STATUS_ACTIVE;
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
     * Sets the ID
     *
     * @param integer $id The new ID for the project
     *
     * @return Project
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * @return \DateTime
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
     * @param \DateTime $deletedAt The date time that this project was deleted
     *
     * @return Project
     */
    public function setDeletedAt(\DateTime $deletedAt)
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
     * Set issues on this project
     *
     * @param Collection $issues The issues collection
     *
     * @return Project
     */
    public function setIssues(Collection $issues)
    {
        $this->issues = $issues;

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

    /**
     * Sets the project owner
     *
     * @param User $owner The project owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * Gets the owner of this project
     *
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Sets the issue prefix
     *
     * @param string $issuePrefix The new issue prefix
     *
     * @return Project
     */
    public function setIssuePrefix($issuePrefix)
    {
        $this->issuePrefix = $issuePrefix;

        return $this;
    }

    /**
     * Gets the issue prefix
     *
     * @return string
     */
    public function getIssuePrefix()
    {
        return $this->issuePrefix;
    }

    /**
     * Sets the project status
     *
     * @param string $status The new project status
     *
     * @throws \InvalidArgumentException If the $status parameter is not a valid value
     *
     * @return Project
     */
    public function setStatus($status)
    {
        if (!in_array($status, [static::STATUS_ARCHIVED, static::STATUS_ACTIVE])) {
            throw new \InvalidArgumentException(
                sprintf('An invalid status (%s) has been provided', $status)
            );
        }

        $this->status = $status;

        return $this;
    }

    /**
     * Gets the current project status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Gets valid status types for projects
     *
     * @param boolean $withFriendlyNames If true, will return friendly status names indexed by their value name
     *
     * @return array
     */
    public static function getStatusTypes($withFriendlyNames = false)
    {
        if (true === $withFriendlyNames) {
            return [
                static::STATUS_ACTIVE => ucwords(static::STATUS_ACTIVE),
                static::STATUS_ARCHIVED => ucwords(static::STATUS_ARCHIVED)
            ];
        }

        return [
            static::STATUS_ACTIVE,
            static::STATUS_ARCHIVED
        ];
    }
}

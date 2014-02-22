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

namespace Tickit\Component\Model\Issue;

use Doctrine\Common\Collections\Collection;
use Tickit\Component\Model\Project\Project;
use Tickit\Component\Model\User\User;

/**
 * Issue model.
 *
 * Represents an individual issue within the application.
 *
 * @package Tickit\Component\Model\Issue
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class Issue
{
    /**
     * The unique identifier
     *
     * @var integer
     */
    protected $id;

    /**
     * The title of the issue
     *
     * @var string
     */
    protected $title;

    /**
     * Comments on the issue
     *
     * @var Collection
     */
    protected $comments;

    /**
     * File attachments
     *
     * @var Collection
     */
    protected $attachments;

    /**
     * User subscriptions on this issue
     *
     * @var Collection
     */
    protected $issueSubscriptions;

    /**
     * The project that this issue is a part of
     *
     * @var Project
     */
    protected $project;

    /**
     * The status of the issue
     *
     * @var IssueStatus
     */
    protected $status;

    /**
     * The full description
     *
     * @var string
     */
    protected $description;

    /**
     * Estimated hours to completion
     *
     * @var float
     */
    protected $estimatedHours;

    /**
     * Actual hours taken so far
     *
     * @var float
     */
    protected $actualHours;

    /**
     * The reporting user
     *
     * @var User
     */
    protected $reportedBy;

    /**
     * The user that the issue is assigned to
     *
     * @var User
     */
    protected $assignedTo;

    /**
     * The date and time the issue was created
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * The date and time that the issue was last updated
     *
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * Gets the id of this issue
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the title of this issue
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Gets the title of this issue
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * Sets the issue status
     *
     * @param IssueStatus $status
     */
    public function setStatus(IssueStatus $status)
    {
        $this->status = $status;
    }


    /**
     * Returns the IssueStatus object representing the status of this issue
     *
     * @return IssueStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the user who this issue is assigned to
     *
     * @param User $assignedTo
     */
    public function setAssignedTo(User $assignedTo)
    {
        $this->assignedTo = $assignedTo;
    }

    /**
     * Gets the user who this issue is assigned to
     *
     * @return User
     */
    public function getAssignedTo()
    {
        return $this->assignedTo;
    }

    /**
     * Sets the issue description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Gets the issue description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the "reported by" user on this issue
     *
     * @param User $reportedBy
     */
    public function setReportedBy(User $reportedBy)
    {
        $this->reportedBy = $reportedBy;
    }


    /**
     * Gets the user that reported this issue
     *
     * @return User
     */
    public function getReportedBy()
    {
        return $this->reportedBy;
    }

    /**
     * Gets the updated time as an instance of DateTime object
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Gets the created time as an instance of DateTime object
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}

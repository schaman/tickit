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
     * The type of the issue
     *
     * @var IssueType
     */
    protected $type;

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
     *
     * @return Issue
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
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
     * Sets the project that this issue relates to
     *
     * @param Project $project The project that this issue relates to
     *
     * @return Issue
     */
    public function setProject(Project $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Gets the project that this issue relates to
     *
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Sets the issue type
     *
     * @param IssueType $type The issue type
     *
     * @return Issue
     */
    public function setType(IssueType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Gets the issue type
     *
     * @return IssueType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the issue status
     *
     * @param IssueStatus $status
     *
     * @return Issue
     */
    public function setStatus(IssueStatus $status)
    {
        $this->status = $status;

        return $this;
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
     *
     * @return Issue
     */
    public function setAssignedTo(User $assignedTo)
    {
        $this->assignedTo = $assignedTo;

        return $this;
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
     *
     * @return Issue
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
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
     * @param User $reportedBy The user who reported the issue
     *
     * @return Issue
     */
    public function setReportedBy(User $reportedBy)
    {
        $this->reportedBy = $reportedBy;

        return $this;
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

    /**
     * Sets actual hours used on this issue
     *
     * @param float $actualHours The actual users spent on this issue
     *
     * @return Issue
     */
    public function setActualHours($actualHours)
    {
        $this->actualHours = $actualHours;

        return $this;
    }

    /**
     * Gets actual hours used on this issue
     *
     * @return float
     */
    public function getActualHours()
    {
        return $this->actualHours;
    }

    /**
     * Sets the number of hours estimated for this issue
     *
     * @param float $estimatedHours The number of hours estimated for the issue
     *
     * @return Issue
     */
    public function setEstimatedHours($estimatedHours)
    {
        $this->estimatedHours = $estimatedHours;

        return $this;
    }

    /**
     * Gets the estimated number of hours for this issue
     *
     * @return float
     */
    public function getEstimatedHours()
    {
        return $this->estimatedHours;
    }
}

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

use Tickit\Component\Model\User\User;

/**
 * The IssueStatusHistory entity represents a snapshot of a issue's status at a given point in time
 *
 * @package Tickit\Component\Model\Issue
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueStatusHistory
{
    /**
     * The unique identifier
     *
     * @var integer
     */
    protected $id;

    /**
     * The issue that this status history entry represents
     *
     * @var Issue
     */
    protected $issue;

    /**
     * The status at this point in time
     *
     * @var IssueStatus
     */
    protected $status;

    /**
     * The user who changed the status
     *
     * @var User
     */
    protected $changedBy;

    /**
     * The date and time that the status was changed
     *
     * @var \DateTime
     */
    protected $changedAt;

    /**
     * Gets the ID for this history entry
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the user who updated the issue history
     *
     * @param User $changedBy
     */
    public function setChangedBy(User $changedBy)
    {
        $this->changedBy = $changedBy;
    }

    /**
     * Gets an instance of the User who changed the status of the issue
     *
     * @return User
     */
    public function getChangedBy()
    {
        return $this->changedBy;
    }

    /**
     * Gets the time that the history was changed as an instance of DateTime
     *
     * @return \DateTime
     */
    public function getChangedAt()
    {
        return $this->changedAt;
    }

    /**
     * Sets the issue status object
     *
     * @param IssueStatus $status
     */
    public function setStatus(IssueStatus $status)
    {
        $this->status = $status;
    }

    /**
     * Gets the issue status object
     *
     * @return IssueStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the issue object
     *
     * @param Issue $issue
     */
    public function setIssue($issue)
    {
        $this->issue = $issue;
    }

    /**
     * Gets the associated issue object
     *
     * @return Issue
     */
    public function getIssue()
    {
        return $this->issue;
    }
}

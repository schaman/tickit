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

use Tickit\Component\Model\IdentifiableInterface;
use Tickit\Component\Model\User\User;

/**
 * The Comment entity represents a comment that is placed on a Issue by a given user
 *
 * @package Tickit\Component\Model\Issue
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class Comment implements IdentifiableInterface
{
    /**
     * The unique identifier for the comment
     *
     * @var integer
     */
    private $id;

    /**
     * The issue that this comment belongs to
     *
     * @var Issue
     */
    private $issue;

    /**
     * The message content on the comment
     *
     * @var string
     */
    private $message;

    /**
     * The user that created the comment
     *
     * @var User
     */
    private $createdBy;

    /**
     * When this comment was created
     *
     * @var \DateTime
     */
    private $createdAt;

    /**
     * When this comment was last updated
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * Get value to identify this model
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the created at datetime
     *
     * @param \DateTime $createdAt The datetime object
     *
     * @return Comment
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Gets the created at datetime
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Sets the user who created this issue
     *
     * @param User $createdBy The user who created this issue
     *
     * @return Comment
     */
    public function setCreatedBy(User $createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * Gets the user who created this issue
     *
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Sets the issue that this comment is for
     *
     * @param Issue $issue The issue
     *
     * @return Comment
     */
    public function setIssue(Issue $issue)
    {
        $this->issue = $issue;

        return $this;
    }

    /**
     * Gets the issue that this comment is for
     *
     * @return Issue
     */
    public function getIssue()
    {
        return $this->issue;
    }

    /**
     * Sets the message content for the comment
     *
     * @param string $message The message
     *
     * @return Comment
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Gets the message content for the comment
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets the updated at datetime
     *
     * @param \DateTime $updatedAt The datetime object
     *
     * @return Comment
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Gets the updated at datetime
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}

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

namespace Tickit\Component\Model\Ticket;

use Doctrine\Common\Collections\Collection;
use Tickit\Component\Model\Project\Project;
use Tickit\Bundle\UserBundle\Entity\User;

/**
 * The User entity represents an individual user within the application
 *
 * @package Tickit\Component\Model\Ticket
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class Ticket
{
    /**
     * The unique identifier
     *
     * @var integer
     */
    protected $id;

    /**
     * The title of the ticket
     *
     * @var string
     */
    protected $title;

    /**
     * Comments on the ticket
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
     * User subscriptions on this ticket
     *
     * @var Collection
     */
    protected $ticketSubscriptions;

    /**
     * The project that this ticket is a part of
     *
     * @var Project
     */
    protected $project;

    /**
     * The status of the ticket
     *
     * @var TicketStatus
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
     * The user that the ticket is assigned to
     *
     * @var User
     */
    protected $assignedTo;

    /**
     * The date and time the ticket was created
     *
     * @var \DateTime
     */
    protected $created;

    /**
     * The date and time that the ticket was last updated
     *
     * @var \DateTime
     */
    protected $updated;

    /**
     * Gets the id of this ticket
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the title of this ticket
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Gets the title of this ticket
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * Sets the ticket status
     *
     * @param TicketStatus $status
     */
    public function setStatus(TicketStatus $status)
    {
        $this->status = $status;
    }


    /**
     * Returns the TicketStatus object representing the status of this ticket
     *
     * @return TicketStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the user who this ticket is assigned to
     *
     * @param User $assignedTo
     */
    public function setAssignedTo(User $assignedTo)
    {
        $this->assignedTo = $assignedTo;
    }

    /**
     * Gets the user who this ticket is assigned to
     *
     * @return User
     */
    public function getAssignedTo()
    {
        return $this->assignedTo;
    }

    /**
     * Sets the ticket description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Gets the ticket description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the "reported by" user on this ticket
     *
     * @param User $reportedBy
     */
    public function setReportedBy(User $reportedBy)
    {
        $this->reportedBy = $reportedBy;
    }


    /**
     * Gets the user that reported this ticket
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
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Gets the created time as an instance of DateTime object
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }
}

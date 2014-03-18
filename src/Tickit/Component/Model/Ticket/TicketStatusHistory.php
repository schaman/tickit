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

namespace Tickit\Component\Model\Ticket;

use Tickit\Component\Avatar\Model\IdentifiableInterface;
use Tickit\Component\Model\User\User;

/**
 * The TicketStatusHistory entity represents a snapshot of a ticket's status at a given point in time
 *
 * @package Tickit\Component\Model\Ticket
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TicketStatusHistory implements IdentifiableInterface
{
    /**
     * The unique identifier
     *
     * @var integer
     */
    protected $id;

    /**
     * The ticket that this status history entry represents
     *
     * @var Ticket
     */
    protected $ticket;

    /**
     * The status at this point in time
     *
     * @var TicketStatus
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
     * Sets the user who updated the ticket history
     *
     * @param User $changedBy
     */
    public function setChangedBy(User $changedBy)
    {
        $this->changedBy = $changedBy;
    }

    /**
     * Gets an instance of the User who changed the status of the ticket
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
     * Sets the ticket status object
     *
     * @param TicketStatus $status
     */
    public function setStatus(TicketStatus $status)
    {
        $this->status = $status;
    }

    /**
     * Gets the ticket status object
     *
     * @return TicketStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the ticket object
     *
     * @param Ticket $ticket
     */
    public function setTicket($ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Gets the associated ticket object
     *
     * @return Ticket
     */
    public function getTicket()
    {
        return $this->ticket;
    }
}

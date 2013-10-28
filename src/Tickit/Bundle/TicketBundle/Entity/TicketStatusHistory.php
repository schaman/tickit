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

namespace Tickit\Bundle\TicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * The TicketStatusHistory entity represents a snapshot of a ticket's status at a given point in time
 *
 * @package Tickit\Bundle\TicketBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="ticket_status_history")
 */
class TicketStatusHistory
{
    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Ticket")
     * @ORM\JoinColumn(name="ticket_id", referencedColumnName="id")
     */
    protected $ticket;

    /**
     * @ORM\ManyToOne(targetEntity="TicketStatus")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    protected $status;

    /**
     * @ORM\ManyToOne(targetEntity="Tickit\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="changed_by_id", referencedColumnName="id")
     */
    protected $changedBy;

    /**
     * @ORM\Column(name="changed_at", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $changedAt;

    /**
     * Gets the ID for this history entry
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the user who updated the ticket history
     *
     * @param \Tickit\Bundle\UserBundle\Entity\User $changedBy
     */
    public function setChangedBy(\Tickit\Bundle\UserBundle\Entity\User $changedBy)
    {
        $this->changedBy = $changedBy;
    }

    /**
     * Gets an instance of the User who changed the status of the ticket
     *
     * @return \Tickit\Bundle\UserBundle\Entity\User
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
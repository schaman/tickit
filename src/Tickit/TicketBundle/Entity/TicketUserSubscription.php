<?php

/*
 * 
 * Tickit, an source web based bug management tool.
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
 * 
 */

namespace Tickit\TicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * The TicketUserSubscription entity represents a user's subscription settings for a ticket
 *
 * @package Tickit\TicketBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="ticket_user_subscriptions")
 */
class TicketUserSubscription
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Tickit\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Ticket", inversedBy="ticketSubscriptions")
     * @ORM\JoinColumn(name="ticket_id", referencedColumnName="id")
     */
    protected $ticket;

    /**
     * @ORM\Column(name="new_comments", type="boolean")
     */
    protected $newComments;

    /**
     * @ORM\Column(name="status_changes", type="boolean")
     */
    protected $statusChanges;
}

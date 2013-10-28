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
use Tickit\Bundle\UserBundle\Entity\User;

/**
 * The Comment entity represents a comment that is placed on a Ticket by a given user
 *
 * @package Tickit\Bundle\TicketBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="comments")
 */
class Comment
{
    /**
     * The unique identifier for the comment
     *
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The ticket that this comment belongs to
     *
     * @var Ticket
     * @ORM\ManyToOne(targetEntity="Ticket", inversedBy="comments")
     * @ORM\JoinColumn(name="ticket_id", referencedColumnName="id")
     */
    protected $ticket;

    /**
     * The user that created the comment
     *
     * @var User
     * @ORM\ManyToOne(targetEntity="Tickit\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * The message content on the comment
     *
     * @var string
     * @ORM\Column(type="string", length=1000)
     */
    protected $message;
}

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

use Tickit\Component\Model\User\User;

/**
 * The TicketUserSubscription entity represents a user's subscription settings for a ticket
 *
 * @package Tickit\Component\Model\Ticket
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TicketUserSubscription
{
    /**
     * The user that this subscription is for
     *
     * @var User
     */
    protected $user;

    /**
     * The ticket that this subscription is against
     *
     * @var Ticket
     */
    protected $ticket;

    /**
     * Whether the user wishes to subscribe for new comment notifications
     *
     * @var boolean
     */
    protected $newComments;

    /**
     * Whether the user wishes to subscribe for ticket status changes
     *
     * @var boolean
     */
    protected $statusChanges;
}

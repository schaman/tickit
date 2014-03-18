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

use Tickit\Component\Model\IdentifiableInterface;
use Tickit\Component\Model\User\User;

/**
 * The Comment entity represents a comment that is placed on a Ticket by a given user
 *
 * @package Tickit\Component\Model\Ticket
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class Comment implements IdentifiableInterface
{
    /**
     * The unique identifier for the comment
     *
     * @var integer
     */
    protected $id;

    /**
     * The ticket that this comment belongs to
     *
     * @var Ticket
     */
    protected $ticket;

    /**
     * The user that created the comment
     *
     * @var User
     */
    protected $user;

    /**
     * The message content on the comment
     *
     * @var string
     */
    protected $message;

    /**
     * Get value to identify this model
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}

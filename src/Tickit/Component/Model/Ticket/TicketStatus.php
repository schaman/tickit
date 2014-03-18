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

/**
 * The TicketStatus entity represents a possible status type for a ticket
 *
 * @package Tickit\Component\Model\Ticket
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TicketStatus implements IdentifiableInterface
{
    /**
     * The unique idenfitier
     *
     * @var integer
     */
    protected $id;

    /**
     * The name of the status
     *
     * @var string
     */
    protected $name;

    /**
     * The color representing this status
     *
     * @var string
     */
    protected $colour;

    /**
     * Gets the ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the name of this status
     *
     * @param string $name
     *
     * @return string
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Gets the name of this status
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the colour of this status
     *
     * @param string $colour A 6 character hexadecimal representation of the colour
     *
     * @return string
     */
    public function setColour($colour = 'FFFFFF')
    {
        $colour = str_replace('#', '', $colour);
        $this->colour = $colour;
    }
}

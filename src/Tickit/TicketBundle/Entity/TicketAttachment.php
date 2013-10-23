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
 * The TicketAttachment entity represents a file attachment on a ticket
 *
 * @package Tickit\TicketBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="ticket_attachments")
 */
class TicketAttachment
{
    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Ticket", inversedBy="attachments")
     * @ORM\JoinColumn(name="ticket_id", referencedColumnName="id")
     */
    protected $ticket;

    /**
     * @ORM\Column(type="string", length=60)
     */
    protected $filename;

    /**
     * @ORM\Column(name="mime_type", type="string", length=30)
     */
    protected $mimeType;

    /**
     * Gets the ID of this comment
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the ticket that this comment is attached to
     *
     * @return Ticket
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * Sets the ticket that this comment is attached to
     *
     * @param Ticket $ticket
     */
    public function setTicket(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Gets the filename of this attachment
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Sets the filename for this attachment
     *
     * @param string $name
     */
    public function setFilename($name)
    {
        $this->filename = $name;
    }

    /**
     * Gets the mime type for this attachment
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Sets the mime type for this attachment
     *
     * @param string $mimeType
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
    }
}

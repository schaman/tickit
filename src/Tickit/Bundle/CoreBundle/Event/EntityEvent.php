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

namespace Tickit\Bundle\CoreBundle\Event;

/**
 * Entity related event.
 *
 * @package Tickit\Bundle\CoreBundle\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class EntityEvent extends AbstractVetoableEvent
{
    /**
     * The entity that is to be deleted
     *
     * @var mixed
     */
    private $entity;

    /**
     * Constructor.
     *
     * @param mixed $entity The entity related to this event
     */
    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    /**
     * Gets the entity on this event
     *
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Sets the entity on this event
     *
     * @param mixed $entity The entity to set
     *
     * @return mixed
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }
}
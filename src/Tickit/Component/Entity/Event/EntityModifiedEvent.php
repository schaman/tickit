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

namespace Tickit\Component\Entity\Event;

/**
 * Entity modification event.
 *
 * This event is used to represent a change to an entity's state in the
 * entity manager.
 *
 * @package Tickit\Component\Entity\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class EntityModifiedEvent extends EntityEvent implements EntityUpdateEventInterface
{
    /**
     * The original entity state before it was modified
     *
     * @var mixed
     */
    private $originalEntity;

    /**
     * Constructor.
     *
     * @param mixed $entity         The entity related to this event
     * @param mixed $originalEntity The original entity state
     */
    public function __construct($entity, $originalEntity)
    {
        $this->setOriginalEntity($originalEntity);

        parent::__construct($entity);
    }


    /**
     * Gets the original entity - before modifications were applied
     *
     * @return mixed
     */
    public function getOriginalEntity()
    {
        return $this->originalEntity;
    }

    /**
     * Sets the original entity state on this object.
     *
     * This is a representation of the entity before the modifications were applied. It
     * is used to determine what has changed between the original state and the new
     * state.
     *
     * @param mixed $originalEntity
     */
    private function setOriginalEntity($originalEntity)
    {
        $this->originalEntity = $originalEntity;
    }
}

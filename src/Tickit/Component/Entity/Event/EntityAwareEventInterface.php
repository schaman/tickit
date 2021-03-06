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

namespace Tickit\Component\Entity\Event;

/**
 * Interface for entity aware events.
 *
 * Any events implementing this interface are guaranteeing that they are
 * responsible for an entity. This proves useful when an object is responsible
 * for the management of events when it does not have insightful details about
 * the entities on those events.
 *
 * @package Tickit\Component\Entity\Event\Interfaces
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @see     Tickit\Component\Entity\Manager\AbstractManager
 */
interface EntityAwareEventInterface
{
    /**
     * Gets the entity associated with this event
     *
     * @return object
     */
    public function getEntity();

    /**
     * Sets the entity associated with this event
     *
     * @param object $entity The entity to attach to the event
     */
    public function setEntity($entity);
}

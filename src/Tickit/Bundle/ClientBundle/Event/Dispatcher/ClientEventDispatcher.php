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

namespace Tickit\Bundle\ClientBundle\Event\Dispatcher;

use Tickit\Bundle\ClientBundle\TickitClientEvents;
use Tickit\Bundle\CoreBundle\Event\AbstractVetoableEvent;
use Tickit\Bundle\CoreBundle\Event\Dispatcher\AbstractEntityEventDispatcher;
use Tickit\Bundle\CoreBundle\Event\EntityEvent;
use Tickit\Bundle\CoreBundle\Event\EntityModifiedEvent;

/**
 * Client event dispatcher.
 *
 * @package Tickit\Bundle\ClientBundle\Event\Dispatcher
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientEventDispatcher extends AbstractEntityEventDispatcher
{
    /**
     * Dispatches events for the "before create" event on the entity
     *
     * @param object $entity The project entity that is about to be created
     *
     * @return AbstractVetoableEvent
     */
    public function dispatchBeforeCreateEvent($entity)
    {
        $beforeEvent = new EntityEvent($entity);
        $beforeEvent = $this->dispatcher->dispatch(TickitClientEvents::CLIENT_BEFORE_CREATE, $beforeEvent);

        return $beforeEvent;
    }

    /**
     * Dispatches events for the "create" event on the entity
     *
     * @param object $entity The project entity that has just been created
     *
     * @return void
     */
    public function dispatchCreateEvent($entity)
    {
        $event = new EntityEvent($entity);
        $this->dispatcher->dispatch(TickitClientEvents::CLIENT_CREATE, $event);
    }

    /**
     * Dispatches events for the "before update" event on the entity
     *
     * @param object $entity The entity that is about to be updated
     *
     * @return AbstractVetoableEvent
     */
    public function dispatchBeforeUpdateEvent($entity)
    {
        $beforeEvent = new EntityEvent($entity);
        $beforeEvent = $this->dispatcher->dispatch(TickitClientEvents::CLIENT_BEFORE_UPDATE, $beforeEvent);

        return $beforeEvent;
    }

    /**
     * Dispatches events for the "update" event on the entity
     *
     * @param object $entity         The entity that has just been updated
     * @param object $originalEntity The entity before any changes were applied
     *
     * @return void
     */
    public function dispatchUpdateEvent($entity, $originalEntity)
    {
        $event = new EntityModifiedEvent($entity, $originalEntity);
        $this->dispatcher->dispatch(TickitClientEvents::CLIENT_UPDATE, $event);
    }

    /**
     * Dispatches events for the "before delete" event on the entity
     *
     * The implementing class should return the event object to guarantee
     * expected behaviour
     *
     * @param object $entity The entity that is about to be deleted
     *
     * @return AbstractVetoableEvent
     */
    public function dispatchBeforeDeleteEvent($entity)
    {
        $beforeEvent = new EntityEvent($entity);
        $beforeEvent = $this->dispatcher->dispatch(TickitClientEvents::CLIENT_BEFORE_DELETE, $beforeEvent);

        return $beforeEvent;
    }

    /**
     * Dispatches events for the "delete" event on the entity
     *
     * @param object $entity The entity that has just been deleted in the entity manager
     *
     * @return void
     */
    public function dispatchDeleteEvent($entity)
    {
        $event = new EntityEvent($entity);
        $this->dispatcher->dispatch(TickitClientEvents::CLIENT_DELETE, $event);
    }
}

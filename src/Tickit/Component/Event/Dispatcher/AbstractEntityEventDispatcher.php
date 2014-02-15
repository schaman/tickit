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

namespace Tickit\Component\Event\Dispatcher;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tickit\Component\Entity\Event\EntityEvent;
use Tickit\Component\Entity\Event\EntityModifiedEvent;

/**
 * Abstract implementation of an entity event dispatcher
 *
 * @package Tickit\Component\Event\Dispatcher
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractEntityEventDispatcher
{
    /**
     * An event dispatcher
     *
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * Event names
     *
     * @var array
     */
    private $eventNames;

    /**
     * Constructor.
     *
     * @param EventDispatcherInterface $dispatcher An event dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        $this->eventNames = $this->getEventNames();
    }

    /**
     * Dispatches events for the "before create" event on the entity
     *
     * The implementing class should return the event object to guarantee
     * expected behaviour
     *
     * @param object $entity The project entity that is about to be created
     *
     * @return EntityEvent
     */
    public function dispatchBeforeCreateEvent($entity)
    {
        $beforeEvent = new EntityEvent($entity);
        $beforeEvent = $this->dispatcher->dispatch($this->eventNames['before_create'], $beforeEvent);

        return $beforeEvent;
    }

    /**
     * Dispatches events for the "create" event on the entity
     *
     * @param object $entity The project entity that has just been created
     */
    public function dispatchCreateEvent($entity)
    {
        $event = new EntityEvent($entity);
        $this->dispatcher->dispatch($this->eventNames['create'], $event);
    }

    /**
     * Dispatches events for the "before update" event on the entity
     *
     * The implementing class should return the event object to guarantee
     * expected behaviour
     *
     * @param object $entity The entity that is about to be updated
     *
     * @return EntityEvent
     */
    public function dispatchBeforeUpdateEvent($entity)
    {
        $beforeEvent = new EntityEvent($entity);
        $beforeEvent = $this->dispatcher->dispatch($this->eventNames['before_update'], $beforeEvent);

        return $beforeEvent;
    }

    /**
     * Dispatches events for the "update" event on the entity
     *
     * @param object $entity         The entity that has just been updated
     * @param object $originalEntity The entity before any changes were applied
     */
    public function dispatchUpdateEvent($entity, $originalEntity)
    {
        $event = new EntityModifiedEvent($entity, $originalEntity);
        $this->dispatcher->dispatch($this->eventNames['update'], $event);
    }

    /**
     * Dispatches events for the "before delete" event on the entity
     *
     * The implementing class should return the event object to guarantee
     * expected behaviour
     *
     * @param object $entity The entity that is about to be deleted
     *
     * @return EntityEvent
     */
    public function dispatchBeforeDeleteEvent($entity)
    {
        $beforeEvent = new EntityEvent($entity);
        $beforeEvent = $this->dispatcher->dispatch($this->eventNames['before_delete'], $beforeEvent);

        return $beforeEvent;
    }

    /**
     * Dispatches events for the "delete" event on the entity
     *
     * @param object $entity The entity that has just been deleted in the entity manager
     */
    public function dispatchDeleteEvent($entity)
    {
        $event = new EntityEvent($entity);
        $this->dispatcher->dispatch($this->eventNames['delete'], $event);
    }

    /**
     * Gets an array of event names
     *
     * @return array
     */
    abstract protected function getEventNames();
}

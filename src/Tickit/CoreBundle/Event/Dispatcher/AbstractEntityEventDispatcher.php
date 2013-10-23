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

namespace Tickit\CoreBundle\Event\Dispatcher;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tickit\CoreBundle\Event\AbstractVetoableEvent;

/**
 * Abstract implementation of an entity event dispatcher
 *
 * @package Tickit\CoreBundle\Event\Dispatcher
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
     * Constructor.
     *
     * @param EventDispatcherInterface $dispatcher An event dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Dispatches events for the "before create" event on the entity
     *
     * The implementing class should return the event object to guarantee
     * expected behaviour
     *
     * @param object $entity The project entity that is about to be created
     *
     * @return AbstractVetoableEvent
     */
    abstract public function dispatchBeforeCreateEvent($entity);

    /**
     * Dispatches events for the "create" event on the entity
     *
     * @param object $entity The project entity that has just been created
     *
     * @return void
     */
    abstract public function dispatchCreateEvent($entity);

    /**
     * Dispatches events for the "before update" event on the entity
     *
     * The implementing class should return the event object to guarantee
     * expected behaviour
     *
     * @param object $entity The entity that is about to be updated
     *
     * @return AbstractVetoableEvent
     */
    abstract public function dispatchBeforeUpdateEvent($entity);

    /**
     * Dispatches events for the "update" event on the entity
     *
     * @param object $entity         The entity that has just been updated
     * @param object $originalEntity The entity before any changes were applied
     *
     * @return void
     */
    abstract public function dispatchUpdateEvent($entity, $originalEntity);

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
    abstract public function dispatchBeforeDeleteEvent($entity);

    /**
     * Dispatches events for the "delete" event on the entity
     *
     * @param object $entity The entity that has just been deleted in the entity manager
     *
     * @return void
     */
    abstract public function dispatchDeleteEvent($entity);
}

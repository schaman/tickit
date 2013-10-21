<?php

namespace Tickit\ClientBundle\Event\Dispatcher;

use Tickit\ClientBundle\TickitClientEvents;
use Tickit\CoreBundle\Event\AbstractVetoableEvent;
use Tickit\CoreBundle\Event\Dispatcher\AbstractEntityEventDispatcher;
use Tickit\CoreBundle\Event\EntityEvent;
use Tickit\CoreBundle\Event\EntityModifiedEvent;

/**
 * Client event dispatcher.
 *
 * @package Tickit\ClientBundle\Event\Dispatcher
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

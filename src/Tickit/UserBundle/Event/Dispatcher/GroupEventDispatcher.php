<?php

namespace Tickit\UserBundle\Event\Dispatcher;

use Tickit\CoreBundle\Event\AbstractVetoableEvent;
use Tickit\CoreBundle\Event\Dispatcher\AbstractEntityEventDispatcher;
use Tickit\UserBundle\Event\GroupBeforeCreateEvent;
use Tickit\UserBundle\Event\GroupBeforeDeleteEvent;
use Tickit\UserBundle\Event\GroupBeforeUpdateEvent;
use Tickit\UserBundle\Event\GroupCreateEvent;
use Tickit\UserBundle\Event\GroupDeleteEvent;
use Tickit\UserBundle\Event\GroupUpdateEvent;
use Tickit\UserBundle\TickitUserEvents;

/**
 * Event dispatcher for the Group entity
 *
 * This class is responsible for firing events related to the management of the
 * Group entity
 *
 * @package Tickit\UserBundle\Event\Dispatcher
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @see     Tickit\UserBundle\Entity\User
 */
class GroupEventDispatcher extends AbstractEntityEventDispatcher
{
    /**
     * Dispatches events for the "before create" event on the entity
     *
     * @param object $entity The group entity that is about to be created
     *
     * @return AbstractVetoableEvent
     */
    public function dispatchBeforeCreateEvent($entity)
    {
        $beforeEvent = new GroupBeforeCreateEvent($entity);
        $beforeEvent = $this->dispatcher->dispatch(TickitUserEvents::GROUP_BEFORE_CREATE, $beforeEvent);

        return $beforeEvent;
    }

    /**
     * Dispatches events for the "create" event on the entity
     *
     * @param object $entity The group entity that has just been created
     *
     * @return void
     */
    public function dispatchCreateEvent($entity)
    {
        $event = new GroupCreateEvent($entity);
        $this->dispatcher->dispatch(TickitUserEvents::GROUP_CREATE, $event);
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
        $beforeEvent = new GroupBeforeUpdateEvent($entity);
        $beforeEvent = $this->dispatcher->dispatch(TickitUserEvents::GROUP_BEFORE_UPDATE, $beforeEvent);

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
        $event = new GroupUpdateEvent($entity, $originalEntity);
        $this->dispatcher->dispatch(TickitUserEvents::GROUP_UPDATE, $event);
    }

    /**
     * Dispatches events for the "before delete" event on the entity
     *
     * @param object $entity The entity that is about to be deleted
     *
     * @return AbstractVetoableEvent
     */
    public function dispatchBeforeDeleteEvent($entity)
    {
        $beforeEvent = new GroupBeforeDeleteEvent($entity);
        $beforeEvent = $this->dispatcher->dispatch(TickitUserEvents::GROUP_BEFORE_DELETE, $beforeEvent);

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
        $event = new GroupDeleteEvent($entity);
        $this->dispatcher->dispatch(TickitUserEvents::GROUP_DELETE, $event);
    }
}

<?php

namespace Tickit\TeamBundle\Event\Dispatcher;

use Tickit\CoreBundle\Event\AbstractVetoableEvent;
use Tickit\CoreBundle\Event\Dispatcher\AbstractEntityEventDispatcher;
use Tickit\TeamBundle\Event\BeforeCreateEvent;
use Tickit\TeamBundle\Event\BeforeDeleteEvent;
use Tickit\TeamBundle\Event\BeforeUpdateEvent;
use Tickit\TeamBundle\Event\CreateEvent;
use Tickit\TeamBundle\Event\DeleteEvent;
use Tickit\TeamBundle\Event\UpdateEvent;
use Tickit\TeamBundle\TickitTeamEvents;

/**
 * Event dispatcher for the Team entity
 *
 * This class is responsible for firing events related to the management of the
 * Team entity
 *
 * @package Tickit\TeamBundle\Event\Dispatcher
 * @author  Mark Wilson <mark@89allport.co.uk>
 * @see     Tickit\TeamBundle\Entity\Team
 */
class TeamEventDispatcher extends AbstractEntityEventDispatcher
{
    /**
     * Dispatches events for the "before create" event on the entity
     *
     * @param object $entity The team entity that is about to be created
     *
     * @return AbstractVetoableEvent
     */
    public function dispatchBeforeCreateEvent($entity)
    {
        $beforeEvent = new BeforeCreateEvent($entity);
        $beforeEvent = $this->dispatcher->dispatch(TickitTeamEvents::TEAM_BEFORE_CREATE, $beforeEvent);

        return $beforeEvent;
    }

    /**
     * Dispatches events for the "create" event on the entity
     *
     * @param object $entity The team entity that has just been created
     *
     * @return void
     */
    public function dispatchCreateEvent($entity)
    {
        $event = new CreateEvent($entity);
        $this->dispatcher->dispatch(TickitTeamEvents::TEAM_CREATE, $event);
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
        $beforeEvent = new BeforeUpdateEvent($entity);
        $beforeEvent = $this->dispatcher->dispatch(TickitTeamEvents::TEAM_BEFORE_UPDATE, $beforeEvent);

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
        $event = new UpdateEvent($entity, $originalEntity);
        $this->dispatcher->dispatch(TickitTeamEvents::TEAM_UPDATE, $event);
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
        $beforeEvent = new BeforeDeleteEvent($entity);
        $beforeEvent = $this->dispatcher->dispatch(TickitTeamEvents::TEAM_BEFORE_DELETE, $beforeEvent);

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
        $event = new DeleteEvent($entity);
        $this->dispatcher->dispatch(TickitTeamEvents::TEAM_DELETE, $event);
    }
}

<?php

namespace Tickit\ProjectBundle\Manager;

use Tickit\CoreBundle\Entity\Interfaces\DeletableEntityInterface;
use Tickit\CoreBundle\Event\AbstractVetoableEvent;
use Tickit\CoreBundle\Manager\AbstractManager;
use Tickit\ProjectBundle\Event\BeforeCreateEvent;
use Tickit\ProjectBundle\Event\BeforeDeleteEvent;
use Tickit\ProjectBundle\Event\BeforeUpdateEvent;
use Tickit\ProjectBundle\Event\CreateEvent;
use Tickit\ProjectBundle\Event\DeleteEvent;
use Tickit\ProjectBundle\Event\UpdateEvent;
use Tickit\ProjectBundle\TickitProjectEvents;

/**
 * Project Manager
 *
 * Responsible for the management of project entities and their interaction
 * with the rest of the application
 *
 * @package Tickit\ProjectBundle\Manager
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class ProjectManager extends AbstractManager
{
    /**
     * Dispatches events for the tickit_project.event.before_create" event
     *
     * @param object $entity The project entity that is about to be created
     *
     * @return AbstractVetoableEvent
     */
    protected function dispatchBeforeCreateEvent($entity)
    {
        $beforeEvent = new BeforeCreateEvent($entity);
        $beforeEvent = $this->dispatcher->dispatch(TickitProjectEvents::PROJECT_BEFORE_CREATE, $beforeEvent);

        return $beforeEvent;
    }

    /**
     * Dispatches events for the tickit_project.event.create" event
     *
     * @param object $entity The project entity that has just been created
     *
     * @return void
     */
    protected function dispatchCreateEvent($entity)
    {
        $event = new CreateEvent($entity);
        $this->dispatcher->dispatch(TickitProjectEvents::PROJECT_CREATE, $event);
    }

    /**
     * Dispatches events for the "tickit_project.event.before_update" event
     *
     * @param object $entity The entity that is about to be updated
     *
     * @return AbstractVetoableEvent
     */
    protected function dispatchBeforeUpdateEvent($entity)
    {
        $beforeEvent = new BeforeUpdateEvent($entity);
        /** @var BeforeUpdateEvent $beforeEvent */
        $beforeEvent = $this->dispatcher->dispatch(TickitProjectEvents::PROJECT_BEFORE_UPDATE, $beforeEvent);

        return $beforeEvent;
    }

    /**
     * Dispatches events for the tickit_project.event.update" event
     *
     * @param object $entity The entity that has just been updated
     *
     * @return void
     */
    protected function dispatchUpdateEvent($entity)
    {

    }

    /**
     * Dispatches events for the tickit_project.event.before_delete" event
     *
     * @param DeletableEntityInterface $entity The entity that is about to be deleted
     *
     * @return AbstractVetoableEvent
     */
    protected function dispatchBeforeDeleteEvent(DeletableEntityInterface $entity)
    {
        $beforeEvent = new BeforeDeleteEvent($entity);
        $beforeEvent = $this->dispatcher->dispatch(TickitProjectEvents::PROJECT_BEFORE_DELETE, $beforeEvent);

        return $beforeEvent;
    }

    /**
     * Dispatches events for the tickit_project.event.delete" event
     *
     * @param DeletableEntityInterface $entity The entity that has just been deleted in the entity manager
     *
     * @return void
     */
    protected function dispatchDeleteEvent(DeletableEntityInterface $entity)
    {
        $event = new DeleteEvent($entity);
        $this->dispatcher->dispatch(TickitProjectEvents::PROJECT_DELETE, $event);
    }

    /**
     * Returns the fully qualified entity class name that the ProjectManager is responsible for
     *
     * @return string
     */
    protected function getEntityClassName()
    {
        return '';
    }
}

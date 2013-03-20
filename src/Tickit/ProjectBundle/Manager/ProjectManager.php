<?php

namespace Tickit\ProjectBundle\Manager;

use Tickit\CoreBundle\Manager\AbstractManager;
use Tickit\ProjectBundle\Entity\Project;
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
     * Creates a project by persisting it in the entity manager
     *
     * Will also dispatch the "tickit_project.event.before_create" and "tickit_project.event.create" events
     *
     * @param Project $project The project to create in the entity manager
     * @param boolean $flush   True to automatically flush changes to the database, false otherwise (defaults to true)
     *
     * @return void
     */
    public function create(Project $project, $flush = true)
    {
        $beforeEvent = new BeforeCreateEvent($project);
        /** @var BeforeCreateEvent $beforeEvent  */
        $beforeEvent = $this->dispatcher->dispatch(TickitProjectEvents::PROJECT_BEFORE_CREATE, $beforeEvent);

        // a subscriber may have updated the project so we re-fetch it from the event
        $project = $beforeEvent->getProject();
        $this->internalPersist($project, $flush);

        $event = new CreateEvent($project);
        $this->dispatcher->dispatch(TickitProjectEvents::PROJECT_CREATE, $event);
    }

    /**
     * Updates a project by flushing changes to the entity manager.
     *
     * Will also dispatch the "tickit_project.event.before_update" and "tickit_project.event.update" events
     *
     * @param Project $project The project to create in the entity manager
     * @param boolean $flush   True to automatically flush changes to the database, false otherwise (defaults to true)
     *
     * @return void
     */
    public function update(Project $project, $flush = true)
    {
        $beforeEvent = new BeforeUpdateEvent($project);
        /** @var BeforeUpdateEvent $beforeEvent */
        $beforeEvent = $this->dispatcher->dispatch(TickitProjectEvents::PROJECT_BEFORE_UPDATE, $beforeEvent);

        // a subscriber may have updated the project so we re-fetch it from the event
        $project = $beforeEvent->getProject();
        $this->internalPersist($project, $flush);

        $event = new UpdateEvent($project);
        $this->dispatcher->dispatch(TickitProjectEvents::PROJECT_UPDATE, $event);
    }

    /**
     * Deletes a project entity from the entity manager
     *
     * @param Project $project
     * @param bool $flush
     */
    public function delete(Project $project, $flush = true)
    {
        $beforeEvent = new BeforeDeleteEvent($project);
        $beforeEvent = $this->dispatcher->dispatch(TickitProjectEvents::PROJECT_BEFORE_DELETE, $beforeEvent);

        if ($beforeEvent->isVetoed()) {
            return;
        }

        $this->internalDelete($project, $flush);

        $event = new DeleteEvent($project);
        $this->dispatcher->dispatch(TickitProjectEvents::PROJECT_DELETE, $event);
    }
}

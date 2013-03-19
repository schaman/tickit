<?php

namespace Tickit\ProjectBundle\Manager;

use Tickit\CoreBundle\Manager\AbstractManager;
use Tickit\ProjectBundle\Entity\Project;
use Tickit\ProjectBundle\Event\BeforeDeleteEvent;
use Tickit\ProjectBundle\Event\CreateEvent;
use Tickit\ProjectBundle\Event\DeleteEvent;
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
     * Persists a project entity to the entity manager
     *
     * @param Project $project The project to persist
     * @param boolean $flush   True to automatically flush changes to the database, false otherwise (defaults to true)
     *
     * @return void
     */
    public function persist(Project $project, $flush = true)
    {
        $this->internalPersist($project, $flush);

        $event = new CreateEvent($project);
        $this->dispatcher->dispatch(TickitProjectEvents::PROJECT_CREATE, $event);
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

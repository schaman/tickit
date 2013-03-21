<?php

namespace Tickit\ProjectBundle\Manager;

use Tickit\CoreBundle\Manager\AbstractManager;

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
     * Returns the original Project from the entity manager.
     *
     * This method takes an entity and returns a copy in its original state
     * from the entity manager. This is used when dispatching entity update
     * events, so a before and after comparison can take place.
     *
     * @param object $entity The entity in its current state
     *
     * @return object
     */
    protected function fetchOriginalEntity($entity)
    {
        $project = $this->em->find('\Tickit\ProjectBundle\Entity\Project', $entity->getId());

        return $project;
    }
}

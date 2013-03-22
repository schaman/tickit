<?php

namespace Tickit\ProjectBundle\Manager;

use Doctrine\Common\Persistence\ObjectRepository;
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
    protected function fetchEntityInOriginalState($entity)
    {
        $project = $this->em->find('\Tickit\ProjectBundle\Entity\Project', $entity->getId());

        return $project;
    }

    /**
     * Gets the entity repository.
     *
     * This method returns the entity repository that is associated with this manager's entity.
     *
     * @return ObjectRepository
     */
    public function getRepository()
    {
        $repository = $this->em->getRepository('TickitProjectBundle:Project');

        return $repository;
    }
}

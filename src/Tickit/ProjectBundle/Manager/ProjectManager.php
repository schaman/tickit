<?php

namespace Tickit\ProjectBundle\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectRepository;
use Tickit\CoreBundle\Manager\AbstractManager;
use Tickit\ProjectBundle\Entity\Project;

/**
 * Project Manager
 *
 * Responsible for the management of project entities and their interaction
 * with the rest of the application
 *
 * @package Tickit\ProjectBundle\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
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

    /**
     * Creates a new project entity in the entity manager.
     *
     * Overrides the default implementation of create() so that we can correctly
     * handle associated attributes.
     *
     * @param object  $entity The project entity that needs creating
     * @param boolean $flush  [Unsupported] True to automatically flush changes to the entity manager
     *
     * @return Project
     */
    public function create($entity, $flush = true)
    {
        $beforeEvent = $this->dispatcher->dispatchBeforeCreateEvent($entity);

        if ($beforeEvent->isVetoed()) {
            return null;
        }

        /** @var Project $entity */
        $originalAttributes = $entity->getAttributes();
        $entity->setAttributes(new ArrayCollection());

        // we flush the entity to get an ID
        $this->em->persist($entity);
        $this->em->flush();

        // not we re-attach the attributes and flush to save them to the database
        $entity->setAttributes($originalAttributes);
        $this->em->flush();

        $this->dispatcher->dispatchCreateEvent($entity);

        return $entity;
    }
}

<?php

/*
 * Tickit, an open source web based bug management tool.
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
 */

namespace Tickit\ProjectBundle\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Tickit\Bundle\CoreBundle\Event\Dispatcher\AbstractEntityEventDispatcher;
use Tickit\Bundle\CoreBundle\Manager\AbstractManager;
use Tickit\ProjectBundle\Entity\Project;
use Tickit\ProjectBundle\Entity\Repository\ProjectRepository;

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
     * The project repo
     *
     * @var ProjectRepository
     */
    protected $projectRepository;

    /**
     * Constructor.
     *
     * @param ProjectRepository             $projectRepository The project repo
     * @param EntityManagerInterface        $em                An entity manager
     * @param AbstractEntityEventDispatcher $dispatcher        An event dispatcher
     */
    public function __construct(ProjectRepository $projectRepository, EntityManagerInterface $em, AbstractEntityEventDispatcher $dispatcher)
    {
        $this->projectRepository = $projectRepository;

        parent::__construct($em, $dispatcher);
    }

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
        $project = $this->projectRepository->find($entity->getId());

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
        return $this->projectRepository;
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

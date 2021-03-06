<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
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

namespace Tickit\Component\Entity\Manager\Project;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Tickit\Component\Entity\Manager\AbstractManager;
use Tickit\Component\Model\IdentifiableInterface;
use Tickit\Component\Model\Project\Project;
use Tickit\Component\Entity\Repository\Project\ProjectRepositoryInterface;
use Tickit\Component\Event\Dispatcher\AbstractEntityEventDispatcher;

/**
 * Project Manager
 *
 * Responsible for the management of project entities and their interaction
 * with the rest of the application
 *
 * @package Tickit\Component\Entity\Manager\Project
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectManager extends AbstractManager
{
    /**
     * The project repo
     *
     * @var ProjectRepositoryInterface
     */
    protected $projectRepository;

    /**
     * Constructor.
     *
     * @param ProjectRepositoryInterface    $projectRepository A project repo
     * @param EntityManagerInterface        $em                An entity manager
     * @param AbstractEntityEventDispatcher $dispatcher        An event dispatcher
     */
    public function __construct(ProjectRepositoryInterface $projectRepository, EntityManagerInterface $em, AbstractEntityEventDispatcher $dispatcher)
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
     * @param IdentifiableInterface $entity The entity in its current state
     *
     * @return object
     */
    protected function fetchEntityInOriginalState(IdentifiableInterface $entity)
    {
        $project = $this->projectRepository->find($entity->getId());

        return $project;
    }

    /**
     * Gets the entity repository.
     *
     * This method returns the entity repository that is associated with this manager's entity.
     *
     * @return ProjectRepositoryInterface
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

    /**
     * Finds a project by ID
     *
     * @param integer $id The project ID to find by
     *
     * @return Project
     */
    public function find($id)
    {
        try {
            return $this->getRepository()->find($id);
        } catch (NoResultException $e) {
            return null;
        }
    }
}

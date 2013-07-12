<?php

namespace Tickit\TeamBundle\Manager;

use Doctrine\Common\Persistence\ObjectRepository;
use Tickit\CoreBundle\Manager\AbstractManager;

/**
 * Team Manager
 *
 * Responsible for the management of team entities and their interaction
 * with the rest of the application
 *
 * @package Tickit\TeamBundle\Manager
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class TeamManager extends AbstractManager
{
    /**
     * Returns the original Team from the entity manager.
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
        $team = $this->em->find('\Tickit\TeamBundle\Entity\Team', $entity->getId());

        return $team;
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
        $repository = $this->em->getRepository('TickitTeamBundle:Team');

        return $repository;
    }
}

<?php

namespace Tickit\UserBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Tickit\CoreBundle\Manager\AbstractManager;
use Tickit\UserBundle\Entity\Group;
use Tickit\UserBundle\Entity\Repository\GroupRepository;

/**
 * Group manager.
 *
 * Responsible for managing anything group related in the application.
 *
 * @package Tickit\UserBundle\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class GroupManager extends AbstractManager
{
    /**
     * Finds a user group by ID
     *
     * @param integer $id The group ID to find by
     *
     * @return Group
     */
    public function find($id)
    {
        $group = $this->em
                      ->getRepository('TickitUserBundle:Group')
                      ->find($id);

        return $group;
    }

    /**
     * Gets the entity repository.
     *
     * This method returns the entity repository that is associated with this manager's entity.
     *
     * @return GroupRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('TickitUserBundle:Group');
    }

    /**
     * Returns the original entity from the entity manager.
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
        return $this->em->find('\Tickit\UserBundle\Entity\Group', $entity->getId());
    }
}

<?php

namespace Tickit\UserBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Tickit\CoreBundle\Event\Dispatcher\AbstractEntityEventDispatcher;
use Tickit\CoreBundle\Manager\AbstractManager;
use Tickit\PermissionBundle\Manager\PermissionManager;
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
     * The permission manager
     *
     * @var PermissionManager
     */
    protected $permissionManager;

    /**
     * Constructor.
     *
     * @param Registry                      $doctrine          The doctrine registry
     * @param AbstractEntityEventDispatcher $dispatcher        The entity event dispatcher
     * @param PermissionManager             $permissionManager The permission manager
     */
    public function __construct(Registry $doctrine, AbstractEntityEventDispatcher $dispatcher, PermissionManager $permissionManager)
    {
        $this->permissionManager = $permissionManager;
        parent::__construct($doctrine, $dispatcher);
    }

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
     * {@inheritDoc}
     *
     * @param object   $entity The group object to create in the entity manager
     * @param boolean  $flush  True to flush changes in the entity manager
     *
     * @return Group
     */
    public function create($entity, $flush = true)
    {
        $permissions = $entity->getPermissions();
        $entity->clearPermissions();

        $group = parent::create($entity, $flush);
        if ($group instanceof Group) {
            $this->permissionManager->updatePermissionDataForGroup($group, $permissions);
        }

        return $group;
    }

    /**
     * {@inheritDoc}
     *
     * @param object  $entity The group object to update in the entity manager
     * @param boolean $flush  True to flush changes in the entity manager
     *
     * @return Group
     */
    public function update($entity, $flush = true)
    {
        $permissions = $entity->getPermissions();
        $entity->clearPermissions();

        $group = parent::update($entity, $flush);
        if ($group instanceof Group) {
            $this->permissionManager->updatePermissionDataForGroup($group, $permissions);
        }

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

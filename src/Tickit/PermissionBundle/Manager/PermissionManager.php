<?php

namespace Tickit\PermissionBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Tickit\PermissionBundle\Entity\Repository\GroupPermissionValueRepository;
use Tickit\PermissionBundle\Entity\Repository\PermissionRepository;
use Tickit\PermissionBundle\Entity\Repository\UserPermissionValueRepository;
use Tickit\PermissionBundle\Model\Permission;

/**
 * Permission manager.
 *
 * Provides functionality for interacting with permission data in the application.
 *
 * @package Tickit\PermissionBundle\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PermissionManager
{
    /**
     * The doctrine registry
     *
     * @var Registry
     */
    protected $doctrine;

    /**
     * Constructor.
     *
     * @param Registry $doctrine The doctrine registry.
     */
    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Gets the permissions repository
     *
     * @return PermissionRepository
     */
    public function getRepository()
    {
        return $this->doctrine->getRepository('TickitPermissionBundle:Permission');
    }

    /**
     * Gets the user permission value repository
     *
     * @return UserPermissionValueRepository
     */
    public function getUserPermissionValueRepository()
    {
        return $this->doctrine->getRepository('TickitPermissionBundle:UserPermissionValue');
    }

    /**
     * Gets the group permission value repository
     *
     * @return GroupPermissionValueRepository
     */
    public function getGroupPermissionValueRepository()
    {
        return $this->doctrine->getRepository('TickitPermissionBundle:GroupPermissionValue');
    }

    /**
     * Gets a collection of permission value data for a user.
     *
     * This method will return a collection containing a user permission values and
     * group permission values for a user and group indexed by the permission's system name.
     *
     * This method intentionally allows a different group than the one associated with the
     * user.
     *
     * @param integer $groupId The ID of the group to fetch permission values for
     * @param integer $userId  The ID of the user to fetch permission values for (optional)
     *
     * @return Collection
     */
    public function getUserPermissionData($groupId, $userId = null)
    {
        $userValues = array();
        if (null !== $userId) {
            $userValues = $this->getUserPermissionValueRepository()
                               ->findAllForUserIndexedByName($userId);
        }

        $groupValues = $this->getGroupPermissionValueRepository()
                            ->findAllForGroupIndexedByName($groupId);

        $collection = new ArrayCollection();
        foreach ($groupValues as $systemName => $groupValue) {
            if (isset($userValues[$systemName])) {
                $userValue = $userValues[$systemName];
            } else {
                $userValue = null;
            }

            $permission = new Permission();
            $permission->setId($groupValue['id']);
            $permission->setName($groupValue['name']);
            $permission->setGroupValue($groupValue['groups'][0]['value']);
            $permission->setUserValue($userValue['users'][0]['value']);

            $collection->offsetSet($groupValue['id'], $permission);
        }

        return $collection;
    }
}

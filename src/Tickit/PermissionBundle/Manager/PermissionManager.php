<?php

namespace Tickit\PermissionBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Tickit\PermissionBundle\Entity\GroupPermissionValue;
use Tickit\PermissionBundle\Entity\Repository\GroupPermissionValueRepository;
use Tickit\PermissionBundle\Entity\Repository\PermissionRepository;
use Tickit\PermissionBundle\Entity\Repository\UserPermissionValueRepository;
use Tickit\PermissionBundle\Entity\UserPermissionValue;
use Tickit\PermissionBundle\Model\Permission;
use Tickit\UserBundle\Entity\Group;
use Tickit\UserBundle\Entity\User;

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
                               ->findAllForUserIndexedBy($userId, 'systemName');
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
            $permission->setOverridden(($userValue !== null));

            $collection->offsetSet($groupValue['id'], $permission);
        }

        return $collection;
    }

    /**
     * Updates permissions for a user using Permission model data.
     *
     * @param User       $user        The user to update permissions for
     * @param Collection $permissions The collection of permission data
     * @param boolean    $flush       False to prevent changes from being flushed to entity manager, defaults to true
     *
     * @return User
     */
    public function updatePermissionDataForUser(User $user, Collection $permissions, $flush = true)
    {
        $em = $this->doctrine->getManager();
        $allPermissions = $this->getRepository()->findAllIndexedById();
        $this->getUserPermissionValueRepository()->deleteAllForUser($user);

        /** @var Permission $permission */
        foreach ($permissions as $permission) {
            if ($permission->getUserValue() !== null) {
                $permissionId = $permission->getId();
                $permissionEntity = $allPermissions[$permissionId];
                $userPermissionValue = new UserPermissionValue();
                $userPermissionValue->setPermission($permissionEntity)
                                    ->setUser($user)
                                    ->setValue($permission->getUserValue());

                $em->persist($userPermissionValue);
            }
        }

        if (false !== $flush) {
            $em->flush();
        }

        $user->setPermissions($permissions);

        return $user;
    }

    /**
     * Updates permissions for a group using Permission model data
     *
     * @param Group      $group       The group to update permissions for
     * @param Collection $permissions The permissions collection
     * @param boolean    $flush       False to prevent changes from being flushed to the entity manager, defaults to true
     *
     * @return Group
     */
    public function updatePermissionDataForGroup(Group $group, Collection $permissions, $flush = true)
    {
        $em = $this->doctrine->getManager();
        $allPermissions = $this->getRepository()->findAllIndexedById();
        $this->getGroupPermissionValueRepository()->deleteAllForGroup($group);

        /** @var Permission $permission */
        foreach ($permissions as $permission) {
            $permissionId = $permission->getId();
            $permissionEntity = $allPermissions[$permissionId];
            $value = new GroupPermissionValue();
            $value->setPermission($permissionEntity);
            $value->setGroup($group);
            if ($permission->getGroupValue()) {
                $value->setValue(true);
            } else {
                $value->setValue(false);
            }

            $em->persist($value);
        }

        if (false !== $flush) {
            $em->flush();
        }

        $group->setPermissions($permissions);

        return $group;
    }
}

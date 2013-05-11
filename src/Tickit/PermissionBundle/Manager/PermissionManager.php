<?php

namespace Tickit\PermissionBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Tickit\PermissionBundle\Entity\Repository\GroupPermissionValueRepository;
use Tickit\PermissionBundle\Entity\Repository\PermissionRepository;
use Tickit\PermissionBundle\Entity\Repository\UserPermissionValueRepository;
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
     * group permission values for a user indexed by the permission's system name.
     *
     * @param User $user The user to fetch permission values for
     *
     * @return Collection
     */
    public function getPermissionDataForUser(User $user)
    {
        $userValues = $this->getUserPermissionValueRepository()
                           ->findAllForUserIndexedByName($user);

        $groupValues = $this->getGroupPermissionValueRepository()
                            ->findAllForGroupIndexedByName($user->getGroup());

        //$collection = new ArrayCollection();
        $collection = array();
        foreach ($groupValues as $systemName => $groupValue) {
            if (isset($userValues[$systemName])) {
                $userValue = $userValues[$systemName];
            } else {
                $userValue = null;
            }

            $permission = array(
                'id' => $groupValue['id'],
                'systemName' => $groupValue['systemName'],
                'name' => $groupValue['name'],
                'user' => $userValue,
                'group' => $groupValue['groups'][0]['value']
            );

            //$collection->offsetSet($systemName, $permission);
            $collection[$systemName] = $permission;
        }

        return $collection;
    }
}

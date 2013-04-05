<?php

namespace Tickit\PermissionBundle\Manager;

use Tickit\UserBundle\Entity\Group;
use Tickit\UserBundle\Entity\User;

/**
 * Permission manager interface.
 *
 * @package Tickit\PermissionBundle\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface PermissionManagerInterface
{
    /**
     * Creates given permissions for a specific user
     *
     * @param array $permissions An array of permission IDs to grant to the user
     * @param User  $user        The user to create permissions for
     *
     * @return mixed
     */
    public function createForUser(array $permissions, User $user);

    /**
     * Updates given permissions for a specific user
     *
     * @param array $permissions An array of UserPermissionValue objects to write
     * @param User  $user        The user to update permissions for
     *
     * @see Tickit\PermissionBundle\Entity\UserPermissionValue
     *
     * @return mixed
     */
    public function updateForUser(array $permissions, User $user);

    /**
     * Updates given permissions for a specific group
     *
     * @param array $permissions An array of GroupPermissionValue objects to write
     * @param Group $group       The group to update permissions for
     *
     * @see Tickit\PermissionBundle\Entity\GroupPermissionValue
     *
     * @return mixed
     */
    public function updateForGroup(array $permissions, Group $group);
}

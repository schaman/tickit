<?php

namespace Tickit\PermissionBundle\Service;
use Tickit\PermissionBundle\Entity\Permission;

/**
 * Provides service level methods for permission related actions
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class PermissionService
{

    /**
     * Calculates a checksum based off an array of permissions
     *
     * @param array $permissions An array of permission objects
     *
     * @throws \InvalidArgumentException
     * @return string
     */
    public function calculateChecksum(array $permissions)
    {
        $permissionString = '';
        /* @var Permission $permission */
        foreach ($permissions as $permission) {
            if (!$permission instanceof Permission) {
                throw new \InvalidArgumentException(
                    sprintf('$permission must be an instance of \Tickit\PermissionBundle\Entity\Permission in %s on line %d', __FILE__, __LINE__)
                );
            }
            $permissionString .= $permission->getSystemName();
        }

        return sha1($permissionString);
    }

}
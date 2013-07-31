<?php

namespace Tickit\PermissionBundle\Hasher;

use Tickit\PermissionBundle\Entity\Permission;

/**
 * Permissions hasher.
 *
 * Hashes permission data into a hash value.
 *
 * @package Tickit\PermissionBundle\Hasher
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PermissionsHasher implements HasherInterface
{
    /**
     * Hashes an array of permissions into a single scalar hash value.
     *
     * @param array $permissions An array of Permission objects
     *
     * @throws \InvalidArgumentException If the $permissions array does not contain Permission instances
     * @throws \InvalidArgumentException If was permissions data is provided
     *
     * @return string
     */
    public function hash(array $permissions)
    {
        if (empty($permissions)) {
            throw new \InvalidArgumentException('The PermissionsHasher cannot hash empty permissions data');
        }

        $permissionString = '';
        foreach ($permissions as $permission) {
            if (!$permission instanceof Permission) {
                throw new \InvalidArgumentException(
                    '$permission must be an instance of \Tickit\PermissionBundle\Entity\Permission'
                );
            }
            $permissionString .= $permission->getSystemName();
        }

        $permissionChecksum = sha1($permissionString);

        return $permissionChecksum;
    }
}

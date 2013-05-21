<?php

namespace Tickit\PermissionBundle\Hasher;

use Tickit\PermissionBundle\Entity\Permission;

/**
 * Permissions hasher.
 *
 * Hashes permission data into a hash value.
 *
 * @package Tickit\PermissionBundle\Hasher
 * @author  James Halsall <jhalsall@rippleffect.com>
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
                $exceptionMsg = sprintf(
                    '$permission must be an instance of \Tickit\PermissionBundle\Entity\Permission in %s on line %d',
                    __FILE__,
                    __LINE__
                );
                throw new \InvalidArgumentException($exceptionMsg);
            }
            $permissionString .= $permission->getSystemName();
        }

        $permissionChecksum = sha1($permissionString);

        return $permissionChecksum;
    }
}

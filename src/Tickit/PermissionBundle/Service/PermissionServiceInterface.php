<?php

namespace Tickit\PermissionBundle\Service;

/**
 * Interface for permission service
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
interface PermissionServiceInterface
{
    /**
     * Returns true if the session storage contains the given permission name, false otherwise
     *
     * @param string $permissionName
     *
     * @return bool
     */
    public function has($permissionName);

    /**
     * Writes an array of permission objects to the current session as a condensed array
     *
     * @param array $permissions An array of permission objects
     */
    public function writeToSession(array $permissions);

}
<?php

namespace Tickit\PermissionBundle\Service;
use Tickit\PermissionBundle\Entity\Permission;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Provides service level methods for permission related actions
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class PermissionService
{
    /* @var \Symfony\Component\HttpFoundation\Session\Session */
    protected $session;

    /**
     * Class constructor, sets up dependencies
     *
     * @param \Symfony\Component\HttpFoundation\Session\Session $session The current Session instance
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Returns true if the session storage contains the given permission name, false otherwise
     *
     * @param string $permissionName
     *
     * @return bool
     * @throws \RuntimeException
     */
    public function has($permissionName)
    {
        $permissions = $this->session->get('permissions');
        if (!is_array($permissions)) {
            throw new \RuntimeException(sprintf('Permissions are not defined in %s::%s', __CLASS__, __FUNCTION__));
        }

        return (in_array($permissionName, $permissions));
    }



    /**
     * Writes an array of permission objects to the current session as a condensed array
     *
     * @param array $permissions An array of \Tickit\PermissionBundle\Entity\Permission objects
     */
    public function writeToSession(array $permissions)
    {
        if (empty($permissions)) {
            return;
        }

        $condensedPermissions = array();

        /* @var \Tickit\PermissionBundle\Entity\Permission $permission */
        foreach ($permissions as $permission) {
            $condensedPermissions[$permission->getSystemName()] = $permission->getName();
        }

        $checksum = $this->calculateChecksum($permissions);
        $this->session->set('permissions-checksum', $checksum);
        $this->session->set('permissions', $condensedPermissions);
    }

    /**
     * Calculates a checksum based off an array of permissions
     *
     * @param array $permissions An array of permission objects
     *
     * @throws \InvalidArgumentException
     * @return string
     */
    protected function calculateChecksum(array $permissions)
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
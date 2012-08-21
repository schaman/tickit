<?php

namespace Tickit\CoreBundle\Entity;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Tickit\PermissionBundle\Service\PermissionService;

/**
 * Overrides the default session class and provides convenience methods for writing and reading data to the session
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class CoreSession extends Session
{
    /* @var \Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage */
    protected $storage;
    /* @var \Tickit\PermissionBundle\Service\PermissionService */
    protected $permissions;

    /**
     * Class constructor, sets up dependencies
     *
     * @param \Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage $storage     The session storage object
     * @param \Tickit\PermissionBundle\Service\PermissionService                     $permissions An instance of the PermissionService class
     */
    public function __construct(NativeSessionStorage $storage, PermissionService $permissions)
    {
        $this->storage = $storage;
        $this->permissions = $permissions;
        parent::__construct($storage);
    }

    /**
     * Writes an array of permission objects to the user's session
     *
     * @param array $permissions An array of \Tickit\PermissionBundle\Entity\Permission objects
     */
    public function writePermissions(array $permissions)
    {
        if (empty($permissions)) {
            return;
        }

        $condensedPermissions = array();

        /* @var \Tickit\PermissionBundle\Entity\Permission $permission */
        foreach ($permissions as $permission) {
            $condensedPermissions[$permission->getSystemName()] = $permission->getName();
        }

        $checksum = $this->permissions->calculateChecksum($permissions);
        $this->set('permissions-checksum', $checksum);
        $this->set('permissions', $condensedPermissions);
    }

}
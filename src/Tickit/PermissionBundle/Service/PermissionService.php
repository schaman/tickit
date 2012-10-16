<?php

namespace Tickit\PermissionBundle\Service;
use Tickit\PermissionBundle\Entity\Permission;
use Symfony\Component\HttpFoundation\Session\Session;
use Tickit\CacheBundle\Cache\CacheFactoryInterface;

/**
 * Provides service level methods for permission related actions
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class PermissionService implements PermissionServiceInterface
{
    const SESSION_PERMISSIONS_CHECKSUM = 'permissions-checksum';
    const SESSION_PERMISSIONS = 'permissions';
    const CACHE_NAMESPACE = 'tickit-permissions';

    /**
     * The current session instance
     *
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    protected $session;

    /**
     * Class constructor, sets up dependencies
     *
     * @param \Symfony\Component\HttpFoundation\Session\Session $session      The current Session instance
     * @param \Tickit\CacheBundle\Cache\CacheFactoryInterface   $cacheFactory The caching factory service
     */
    public function __construct(Session $session, CacheFactoryInterface $cacheFactory)
    {
        $this->session = $session;
        $this->cache = $cacheFactory->factory('file', array('default_namespace' => static::CACHE_NAMESPACE));
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    public function getSession()
    {
        return $this->session;
    }



    /**
     * {@inheritdoc}
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
        $this->session->set(static::SESSION_PERMISSIONS_CHECKSUM, $checksum);
        $this->session->set(static::SESSION_PERMISSIONS, $condensedPermissions);
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

        $permissionChecksum = sha1($permissionString);
        $this->cache->write($this->session->getId(), $permissionChecksum);

        return $permissionChecksum;
    }

}
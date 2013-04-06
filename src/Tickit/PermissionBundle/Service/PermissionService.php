<?php

namespace Tickit\PermissionBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectManager;
use Tickit\CacheBundle\Cache\Cache;
use Tickit\PermissionBundle\Entity\Permission;
use Symfony\Component\HttpFoundation\Session\Session;
use Tickit\CacheBundle\Cache\CacheFactoryInterface;
use Tickit\UserBundle\Entity\User;

/**
 * Provides service level methods for permission related actions
 *
 * @package Tickit\PermissionBundle\Service
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PermissionService implements PermissionServiceInterface
{
    const SESSION_PERMISSIONS_CHECKSUM = 'permissions-checksum';
    const SESSION_PERMISSIONS = 'permissions';
    const CACHE_NAMESPACE = 'tickit-permissions';

    /**
     * The current session instance
     *
     * @var Session
     */
    protected $session;

    /**
     * The default entity manager instance
     *
     * @var ObjectManager
     */
    protected $em;

    /**
     * Caching layer instance
     *
     * @var Cache
     */
    protected $cache;

    /**
     * Constructor.
     *
     * @param Session               $session      The current Session instance
     * @param Registry              $doctrine     The doctrine registry
     * @param CacheFactoryInterface $cacheFactory The caching factory service
     */
    public function __construct(Session $session, Registry $doctrine, CacheFactoryInterface $cacheFactory)
    {
        $this->session = $session;
        $this->em = $doctrine->getManager();
        $this->cache = $cacheFactory->factory('file', array('default_namespace' => static::CACHE_NAMESPACE));
    }

    /**
     * Returns true if the session storage contains the given permission name, false otherwise
     *
     * @param string $permissionName The name of the permission
     *
     * @throws \RuntimeException If no permissions are defined in the session
     *
     * @return bool
     */
    public function has($permissionName)
    {
        $permissions = $this->session->get(static::SESSION_PERMISSIONS);
        if (!is_array($permissions)) {
            throw new \RuntimeException(sprintf('Permissions are not defined in %s::%s', __CLASS__, __FUNCTION__));
        }

        return (array_key_exists($permissionName, $permissions));
    }

    /**
     * {@inheritdoc}
     *
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Writes an array of permission objects to the current session as a condensed array
     *
     * @param array $permissions An array of permission objects
     *
     * @return void
     */
    public function writeToSession(array $permissions)
    {
        if (empty($permissions)) {
            return;
        }

        $condensedPermissions = array();

        /* @var Permission $permission */
        foreach ($permissions as $permission) {
            $condensedPermissions[$permission->getSystemName()] = $permission->getName();
        }

        $checksum = $this->calculateChecksum($permissions);
        $this->session->set(static::SESSION_PERMISSIONS_CHECKSUM, $checksum);
        $this->session->set(static::SESSION_PERMISSIONS, $condensedPermissions);
    }

    /**
     * Loads permissions for a given user from the database layer and returns them
     *
     * @param User $user The user to load from the provider
     *
     * @return array
     */
    public function loadFromProvider(User $user)
    {
        $permissions = $this->em
                            ->getRepository('TickitPermissionBundle:Permission')
                            ->findAllForUser($user);

        return $permissions;
    }

    /**
     * Calculates a checksum based off an array of permissions
     *
     * @param array $permissions An array of permission objects
     *
     * @throws \InvalidArgumentException
     *
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

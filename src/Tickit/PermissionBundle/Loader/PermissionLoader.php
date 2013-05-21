<?php

namespace Tickit\PermissionBundle\Loader;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Tickit\CacheBundle\Cache\Cache;
use Tickit\CacheBundle\Cache\CacheFactoryInterface;
use Tickit\PermissionBundle\Entity\Permission;
use Tickit\PermissionBundle\Hasher\PermissionsHasher;
use Tickit\UserBundle\Entity\User;

/**
 * Permission loader.
 *
 * Loads permissions into the current session.
 *
 * @package Tickit\PermissionBundle\Loader
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class PermissionLoader implements LoaderInterface
{
    const SESSION_PERMISSIONS_HASH = 'permissions-hash';
    const SESSION_PERMISSIONS = 'permissions';
    const CACHE_NAMESPACE = 'tickit-permissions';

    /**
     * The session instance
     *
     * @var SessionInterface
     */
    protected $session;

    /**
     * The doctrine registry
     *
     * @var Registry
     */
    protected $doctrine;

    /**
     * The file cache
     *
     * @var Cache
     */
    protected $cache;

    /**
     * Constructor.
     *
     * @param SessionInterface      $session      The current Session instance
     * @param Registry              $doctrine     The doctrine registry
     * @param CacheFactoryInterface $cacheFactory The caching factory service
     */
    public function __construct(SessionInterface $session, Registry $doctrine, CacheFactoryInterface $cacheFactory)
    {
        $this->session = $session;
        $this->doctrine = $doctrine;
        $this->cache = $cacheFactory->factory('file', array('default_namespace' => static::CACHE_NAMESPACE));
    }

    /**
     * Loads permissions into the current context for the given user.
     *
     * @param User $user The user to load permissions for
     *
     * @return void
     */
    public function loadForUser(User $user)
    {
        $permissions = $this->doctrine
                            ->getRepository('TickitPermissionBundle:Permission')
                            ->findAllForUser($user);

        if (empty($permissions)) {
            return;
        }

        $condensedPermissions = array();

        /** @var Permission $permission */
        foreach ($permissions as $permission) {
            $condensedPermissions[$permission->getSystemName()] = $permission->getName();
        }

        $hasher = new PermissionsHasher();
        $hash = $hasher->hash($permissions);
        $this->cache->write(static::SESSION_PERMISSIONS_HASH . '-' . $this->session->getId(), $hash);

        $this->session->set(static::SESSION_PERMISSIONS, $condensedPermissions);
        $this->session->set(static::SESSION_PERMISSIONS_HASH, $hash);
    }

    /**
     * Loads the permissions hash from disk for the currently active session
     *
     * @return string
     */
    public function loadPermissionHashFromCache()
    {
        $id = $this->session->getId();
        $hash = $this->cache->read(static::SESSION_PERMISSIONS_HASH . '-' . $id);

        return $hash;
    }
}

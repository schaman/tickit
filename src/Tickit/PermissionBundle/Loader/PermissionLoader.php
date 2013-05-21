<?php

namespace Tickit\PermissionBundle\Loader;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Tickit\CacheBundle\Cache\CacheFactoryInterface;
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
    const SESSION_PERMISSIONS_CHECKSUM = 'permissions-checksum';
    const SESSION_PERMISSIONS = 'permissions';
    const CACHE_NAMESPACE = 'tickit-permissions';

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
        $this->em = $doctrine->getManager();
        $this->cache = $cacheFactory->factory('file', array('default_namespace' => static::CACHE_NAMESPACE));
    }

    /**
     * Loads permissions into the current context for the given user.
     *
     * @param User $user The user to load permissions for
     *
     * @return mixed
     */
    public function loadForUser(User $user)
    {

    }
}

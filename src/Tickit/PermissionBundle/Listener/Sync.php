<?php

namespace Tickit\PermissionBundle\Listener;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Tickit\PermissionBundle\Service\PermissionServiceInterface;
use Tickit\PermissionBundle\Service\PermissionService;
use Tickit\CacheBundle\Cache\CacheFactoryInterface;

/**
 * Synchronisation class that listens for controller requests and updates
 * the current user's permissions in session (if they have updated since last load)
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class Sync
{
    /**
     * The application security context instance
     *
     * @var \Symfony\Component\Security\Core\SecurityContext
     */
    protected $context;

    /**
     * The permission service
     *
     * @var \Tickit\PermissionBundle\Service\PermissionServiceInterface
     */
    protected $permissions;

    /**
     * The file cache (in future this will be configurable to change the cache type)
     *
     * @var \Tickit\CacheBundle\Cache\Cache
     */
    protected $cache;

    /**
     * Class constructor
     *
     * @param \Symfony\Component\Security\Core\SecurityContext            $context      The application SecurityContext instance
     * @param \Tickit\PermissionBundle\Service\PermissionServiceInterface $permissions  The permission service instance
     * @param \Tickit\CacheBundle\Cache\CacheFactoryInterface             $cacheFactory The caching factory service
     */
    public function __construct(SecurityContext $context, PermissionServiceInterface $permissions, CacheFactoryInterface $cacheFactory)
    {
        $this->context = $context;
        $this->permissions = $permissions;
        $this->cache = $cacheFactory->factory('file', array('default_namespace' => PermissionService::CACHE_NAMESPACE));
    }

    /**
     * Updates the user's permissions in the session if they have changed since the last request
     *
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     */
    public function onCoreController(FilterControllerEvent $event)
    {
        //if this isn't the main http request, then we aren't interested...
        if (HttpKernel::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $token = $this->context->getToken();
        if ($token->isAuthenticated()) {
            $session = $this->permissions->getSession();
            $sessionChecksum = $session->get(PermissionService::SESSION_PERMISSIONS_CHECKSUM);
            $existingChecksum = $this->cache->read($session->getId());
            if ($existingChecksum != $sessionChecksum) {
                $permissions = $this->permissions->loadFromProvider($token->getUser());
                $this->permissions->writeToSession($permissions);
            }
        }
    }


}

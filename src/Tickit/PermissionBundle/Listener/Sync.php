<?php

namespace Tickit\PermissionBundle\Listener;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Tickit\PermissionBundle\Loader\LoaderInterface;
use Tickit\PermissionBundle\Loader\PermissionLoader;
use Tickit\UserBundle\Entity\User;

/**
 * Permission synchronisation service.
 *
 * Synchronisation class that listens for controller requests and updates
 * the current user's permissions in session (if they have updated since last load)
 *
 * @package Tickit\PermissionBundle\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class Sync
{
    /**
     * The application security context instance
     *
     * @var SecurityContext
     */
    protected $context;

    /**
     * The permission loader
     *
     * @var LoaderInterface
     */
    protected $permissionsLoader;

    /**
     * The current session
     *
     * @var SessionInterface
     */
    protected $session;

    /**
     * Constructor.
     *
     * @param SecurityContext  $context           The application SecurityContext instance
     * @param LoaderInterface  $permissionsLoader The permissions loader
     * @param SessionInterface $session           The current session
     */
    public function __construct(SecurityContext $context, LoaderInterface $permissionsLoader, SessionInterface $session)
    {
        $this->context = $context;
        $this->permissionsLoader = $permissionsLoader;
        $this->session = $session;
    }

    /**
     * Updates the user's permissions in the session if they have changed since the last request
     *
     * @param FilterControllerEvent $event The controller event
     *
     * @return void
     */
    public function synchronizePermissions(FilterControllerEvent $event)
    {
        // if this isn't the main http request, then we aren't interested...
        if (HttpKernel::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $token = $this->context->getToken();
        if ($token->isAuthenticated()) {
            $user = $token->getUser();

            if (!$user instanceof User) {
                return;
            }

            $sessionHash = $this->session->get(PermissionLoader::SESSION_PERMISSIONS_HASH);
            $existingHash = $this->permissionsLoader->loadPermissionHashFromCache();

            // if the hashes don't match between the cache and the session, then permissions
            // have changed and need to be reloaded into the current session
            if ($existingHash != $sessionHash) {
                $this->permissionsLoader->loadForUser($user);
            }
        }
    }
}

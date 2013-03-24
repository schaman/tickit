<?php

namespace Tickit\UserBundle\Listener;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Tickit\CoreBundle\Entity\CoreSession;
use Tickit\PermissionBundle\Service\PermissionServiceInterface;
use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Entity\UserSession;

/**
 * Handles the security context's login event
 *
 * @package Tickit\UserBundle\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class Login
{
    /**
     * The service container
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * The entity manager
     *
     * @var ObjectManager
     */
    protected $em;

    /**
     * The session object
     *
     * @var CoreSession
     */
    protected $session;

    /**
     * The permissions service
     *
     * @var PermissionServiceInterface
     */
    protected $permissions;

    /**
     * Constructor.
     *
     * @param ContainerInterface         $container   The dependency injection container
     * @param CoreSession                $session     The current user's session instance
     * @param Registry                   $doctrine    The doctrine registry
     * @param PermissionServiceInterface $permissions The permission service
     */
    public function __construct(ContainerInterface $container, CoreSession $session, Doctrine $doctrine, PermissionServiceInterface $permissions)
    {
        $this->container = $container;
        $this->em = $doctrine->getManager();
        $this->session = $session;
        $this->permissions = $permissions;
    }

    /**
     * Post login event handler. Records the user's session in the database and triggers the loading of permissions
     *
     * @param InteractiveLoginEvent $event The login event
     *
     * @return void
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if (!$user instanceof User) {
            return;
        }

        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $sessionToken = $this->session->getId();

        $userSession = new UserSession();
        $userSession->setUser($user);
        $userSession->setIp($ipAddress);
        $userSession->setSessionToken($sessionToken);
        $this->em->persist($userSession);
        $this->em->flush();

        $permissions = $this->permissions->loadFromProvider($user);
        $this->permissions->writeToSession($permissions);
    }
}

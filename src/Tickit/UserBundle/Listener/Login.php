<?php

namespace Tickit\UserBundle\Listener;

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
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class Login
{

    protected $container;
    protected $em;
    protected $session;
    protected $permissions;

    /**
     * Class constructor, sets dependencies
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface   $container   The dependency injection container
     * @param \Tickit\CoreBundle\Entity\CoreSession                       $session     The current user's session instance
     * @param \Doctrine\Bundle\DoctrineBundle\Registry                    $doctrine    The doctrine registry
     * @param \Tickit\PermissionBundle\Service\PermissionServiceInterface $permissions The permission service
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
     * @param \Symfony\Component\Security\Http\Event\InteractiveLoginEvent $event
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
<?php

namespace Tickit\UserBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\Session\Session;
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

    /**
     * Class constructor, sets dependencies
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container The dependency injection container
     * @param \Symfony\Component\HttpFoundation\Session\Session         $session   The current user's session instance
     * @param \Doctrine\Bundle\DoctrineBundle\Registry                  $doctrine  The doctrine registry
     */
    public function __construct(ContainerInterface $container, Session $session, Doctrine $doctrine)
    {
        $this->container = $container;
        $this->em = $doctrine->getManager();
        $this->session = $session;
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

        /*$permissions = $this->em
                            ->getRepository('TickitPermissionBundle:Permission')
                            ->findAllForUser($user);*/
    }

}
<?php

namespace Tickit\UserBundle\Listener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Tickit\CoreBundle\Entity\CoreSession;
use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Entity\UserSession;

/**
 * Handles the security context's login event
 *
 * @package Tickit\UserBundle\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class LoginListener
{
    /**
     * The entity manager
     *
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * The session object
     *
     * @var CoreSession
     */
    protected $session;

    /**
     * Constructor.
     *
     * @param CoreSession            $session The current user's session instance
     * @param EntityManagerInterface $em      An entity manager
     */
    public function __construct(CoreSession $session, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->session = $session;
    }

    /**
     * Post login event handler. Records the user's session in the database
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

        $ipAddress = @$_SERVER['REMOTE_ADDR'] ?: 'unknown';
        $sessionToken = $this->session->getId();

        $userSession = new UserSession();
        $userSession->setUser($user);
        $userSession->setIp($ipAddress);
        $userSession->setSessionToken($sessionToken);
        $this->em->persist($userSession);
        $this->em->flush();
    }
}

<?php

/*
 * Tickit, an open source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tickit\Bundle\UserBundle\Listener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Tickit\Bundle\CoreBundle\Entity\CoreSession;
use Tickit\Bundle\UserBundle\Entity\User;
use Tickit\Bundle\UserBundle\Entity\UserSession;

/**
 * Handles the security context's login event
 *
 * @package Tickit\Bundle\UserBundle\Listener
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

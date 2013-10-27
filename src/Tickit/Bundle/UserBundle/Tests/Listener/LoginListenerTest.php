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

namespace Tickit\UserBundle\Tests\Listener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Tickit\Bundle\CoreBundle\Tests\AbstractUnitTest;
use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Entity\UserSession;
use Tickit\UserBundle\Listener\LoginListener;

/**
 * Login listener tests.
 *
 * @package Tickit\UserBundle\Tests\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class LoginListenerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $em;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $session;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->em = $this->getMockEntityManager();
        $this->session = $this->getMockBuilder('Tickit\Bundle\CoreBundle\Entity\CoreSession')
                              ->disableOriginalConstructor()
                              ->getMock();
    }

    /**
     * Tests the onSecurityInteractiveLogin() method
     */
    public function testOnSecurityInteractiveLoginCreatesSessionForUser()
    {
        $user = new User();
        $event = $this->getLoginEvent($user);

        $expectedSession = new UserSession();
        $expectedSession->setIp('unknown');
        $expectedSession->setSessionToken('session-id');
        $expectedSession->setUser($user);

        $this->session->expects($this->once())
                      ->method('getId')
                      ->will($this->returnValue('session-id'));

        $this->em->expects($this->once())
                 ->method('persist')
                 ->with($expectedSession);

        $this->em->expects($this->once())
                 ->method('flush');

        $this->getListener()->onSecurityInteractiveLogin($event);
    }

    /**
     * Tests the onSecurityInteractiveLogin() method
     */
    public function testOnSecurityInteractiveLoginDoesNotCreateSessionForNoUser()
    {
        $event = $this->getLoginEvent();

        $this->session->expects($this->never())
                      ->method('getId');

        $this->em->expects($this->never())
                 ->method('persist');

        $this->em->expects($this->never())
                 ->method('flush');

        $this->getListener()->onSecurityInteractiveLogin($event);
    }

    /**
     * Gets a new interactive login event
     *
     * @param User $user The user to create a login event for
     *
     * @return InteractiveLoginEvent
     */
    private function getLoginEvent(User $user = null)
    {
        $request = new Request();
        $token = new UsernamePasswordToken(
            $user !== null ? $user : 'anon.',
            $user !== null ? $user->getPlainPassword() : '',
            'main'
        );
        $event = new InteractiveLoginEvent($request, $token);

        return $event;
    }

    /**
     * Gets a new listener instance
     *
     * @return LoginListener
     */
    private function getListener()
    {
        return new LoginListener($this->session, $this->em);
    }
}

<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
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

namespace Tickit\Bundle\UserBundle\Tests\Listener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Tests\Fixtures\KernelForTest;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tickit\Component\Test\AbstractUnitTest;
use Tickit\Bundle\UserBundle\Listener\KernelExceptionListener;

/**
 * KernelExceptionListener tests
 *
 * @package Tickit\Bundle\UserBundle\Tests\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class KernelExceptionListenerTest extends AbstractUnitTest
{
    /**
     * Tests the onKernelException() method
     *
     * @return void
     */
    public function testOnKernelExceptionDoesNotInterceptAuthenticatedNonAjaxRequest()
    {
        $event = $this->getEvent();
        $event->getRequest()->server->set('PHP_AUTH_USER', 'dummy user');

        $listener = $this->getListener();
        $listener->onKernelException($event);

        $this->assertEquals(200, $event->getResponse()->getStatusCode());
        $this->assertEquals('content', $event->getResponse()->getContent());
    }

    /**
     * Tests the onKernelException() method
     *
     * @return void
     */
    public function testOnKernelExceptionPerformsRedirectForNonAjaxRequestWithoutUser()
    {
        $event = $this->getEvent();

        $listener = $this->getListener();
        $listener->onKernelException($event);

        $this->assertEquals(302, $event->getResponse()->getStatusCode());
    }

    /**
     * Tests the onKernelException() method
     *
     * @return void
     */
    public function testOnKernelExceptionDoesNotInterceptNonAccessDeniedException()
    {
        $event = $this->getEvent(new \InvalidArgumentException());
        $listener = $this->getListener();
        $listener->onKernelException($event);

        $this->assertEquals(200, $event->getResponse()->getStatusCode());
        $this->assertEquals('content', $event->getResponse()->getContent());
    }

    /**
     * Tests the onKernelException() method
     *
     * @return void
     */
    public function testOnKernelExceptionSets403ResponseForAccessDeniedExceptionAndAjaxRequest()
    {
        $event = $this->getEvent();
        $event->getRequest()->headers->set('X-Requested-With', 'XMLHttpRequest');
        $listener = $this->getListener();
        $listener->onKernelException($event);

        $this->assertEquals(403, $event->getResponse()->getStatusCode());
        $this->assertEquals('', $event->getResponse()->getContent());
    }

    /**
     * Gets an event instance
     *
     * @param mixed $exception The exception to be attached to the event
     *
     * @return GetResponseForExceptionEvent
     */
    private function getEvent($exception = null)
    {
        $kernel = new KernelForTest('test', false);

        $request = Request::createFromGlobals();
        $request->setSession(new Session(new MockArraySessionStorage()));

        $response = new Response('content');

        if (null === $exception) {
            $exception = new AccessDeniedException();
        }

        $event = new GetResponseForExceptionEvent($kernel, $request, 1, $exception);
        $event->setResponse($response);

        return $event;
    }

    private function getListener()
    {
        $securityContext = $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContextInterface')
                                ->disableOriginalConstructor()
                                ->getMock();

        $router = $this->getMockBuilder('Symfony\Component\Routing\Router')
                       ->disableOriginalConstructor()
                       ->getMock();

        $router->expects($this->any())
               ->method('generate')
               ->will($this->returnValue('some_route'));

        $listener = new KernelExceptionListener($securityContext, $router);

        return $listener;
    }
}

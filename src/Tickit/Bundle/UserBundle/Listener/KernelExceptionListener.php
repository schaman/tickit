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

namespace Tickit\Bundle\UserBundle\Listener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Kernel exception listener.
 *
 * Handles exceptions thrown within the kernel.
 *
 * @package Tickit\Bundle\UserBundle\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class KernelExceptionListener
{
    /**
     * Security context
     *
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * Router
     *
     * @var Router
     */
    private $router;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext Security context
     * @param Router                   $router          Router
     */
    public function __construct(SecurityContextInterface $securityContext, Router $router)
    {
        $this->securityContext = $securityContext;
        $this->router = $router;
    }

    /**
     * Handles a kernel exception.
     *
     * If the request is an XmlHttpRequest and an AccessDeniedException has been thrown then
     * this method will set the response to a 403 rather than redirecting to /login. If this exception occurs but
     * the request is not an XmlHttpRequest then we intercept the default security component 302 response
     * with our own so additional header/session clearing can occur.
     *
     * @param GetResponseForExceptionEvent $event The exception event
     *
     * @return void
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $request = $event->getRequest();
        $exception = $event->getException();

        if ($exception instanceof AccessDeniedException) {
            if ($request->isXmlHttpRequest()) {
                $response = new Response('', 403);

                // Access denied AND there is no current user, so we can clean up the session
                // This stops an issue with the user being redirected between login and dashboard via the JS routing
                if ($request->getUser() === null) {
                    $this->invalidateUserSession($request, $response);
                }

                $event->setResponse($response);
            } else {
                // Access denied AND there is no current user, so we can clean up the session
                // This stops an issue where the 302 redirect to login (which could be handled by the security
                // component) doesn't contain enough headers to clear the user data in JS
                if ($request->getUser() === null) {
                    $response = new RedirectResponse($this->router->generate('fos_user_security_login'));

                    $this->invalidateUserSession($request, $response);

                    $event->setResponse($response);
                }
            }
        }
    }

    /**
     * Invalidate the user's session and remove "uid" cookie
     *
     * @param Request  $request      Request
     * @param Response $response     Response
     */
    private function invalidateUserSession(Request $request, Response $response)
    {
        $this->securityContext->setToken(null);

        $request->getSession()->invalidate();

        $response->headers->clearCookie('uid');
    }
}

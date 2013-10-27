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

namespace Tickit\UserBundle\Tests\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Tickit\Bundle\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\Bundle\CoreBundle\Tests\AbstractUnitTest;
use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Security\AuthenticationHandler;

/**
 * AuthenticationHandler tests
 *
 * @package Tickit\UserBundle\Tests\Security
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AuthenticationHandlerTest extends AbstractUnitTest
{
    /**
     * Tests the onAuthenticationFailure() method
     *
     * @return void
     */
    public function testOnAuthenticationFailureReturnsCorrectResponse()
    {
        $handler = new AuthenticationHandler();
        $request = new Request();
        $exception = new AuthenticationException('Invalid username');

        $response = $handler->onAuthenticationFailure($request, $exception);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $response);
        $expected = array(
            'success' => false,
            'error' => 'Invalid username'
        );
        $this->assertEquals((object) $expected, json_decode($response->getContent()));
    }

    /**
     * Tests the onAuthenticationSuccess() method
     *
     * @return void
     */
    public function testOnAuthenticationSuccessReturnsCorrectResponse()
    {
        $handler = new AuthenticationHandler();
        $session = new Session(new MockArraySessionStorage());
        $session->setId('dwoairae9fowadowa');
        $request = new Request();
        $request->setSession($session);

        $user = new User();
        $user->setId(1)
             ->setUsername('username');

        $token = new UsernamePasswordToken($user, 'password', 'main');

        $response = $handler->onAuthenticationSuccess($request, $token);
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $response);
        $expected = array(
            'success' => true,
            'userId' => $user->getId(),
            'sessionId' => $session->getId(),
            'url' => '/dashboard'
        );
        $this->assertEquals((object) $expected, json_decode($response->getContent()));
    }
}

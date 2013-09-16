<?php

namespace Tickit\UserBundle\Tests\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\UserBundle\Security\AuthenticationHandler;

/**
 * AuthenticationHandler tests
 *
 * @package Tickit\UserBundle\Tests\Security
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AuthenticationHandlerTest extends AbstractFunctionalTest
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

        $container = $this->createClient()->getContainer();
        $user = $container->get('doctrine')->getRepository('TickitUserBundle:User')->findOneByUsername('james');

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

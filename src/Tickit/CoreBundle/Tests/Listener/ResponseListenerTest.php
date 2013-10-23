<?php

namespace Tickit\CoreBundle\Tests\Listener;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Tests\Fixtures\KernelForTest;
use Symfony\Component\HttpKernel\HttpKernel;
use Tickit\CoreBundle\Listener\ResponseListener;

/**
 * ResponseListener tests
 *
 * @package Tickit\CoreBundle\Tests\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ResponseListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the onResponse() method
     */
    public function testOnResponseAddsEnvironmentCookie()
    {
        $kernel = new KernelForTest('test', false);
        $event = new FilterResponseEvent($kernel, new Request(), HttpKernel::MASTER_REQUEST, new Response());

        $listener = $this->getResponseListener('prod');
        $listener->onResponse($event);

        $cookies = $event->getResponse()->headers->getCookies(ResponseHeaderBag::COOKIES_ARRAY);
        $domainCookies = array_shift($cookies);
        $pathCookies = array_shift($domainCookies);
        $this->assertCount(1, $pathCookies);

        /** @var Cookie $cookie */
        $cookie = $pathCookies['env'];
        $this->assertEquals('env', $cookie->getName());
        $this->assertEquals('prod', $cookie->getValue());
    }

    /**
     * Tests the onResponse() method
     */
    public function testOnResponseDoesNotAddEnvironmentCookieForSubRequests()
    {
        $kernel = new KernelForTest('test', false);
        $event = new FilterResponseEvent($kernel, new Request(), HttpKernel::SUB_REQUEST, new Response());

        $listener = $this->getResponseListener('prod');
        $listener->onResponse($event);

        $this->assertEmpty($event->getResponse()->headers->getCookies());
    }

    /**
     * Gets an instance of the listener
     *
     * @param string $env The environment name to instantiate the listener with
     *
     * @return ResponseListener
     */
    private function getResponseListener($env)
    {
        return new ResponseListener($env);
    }
}

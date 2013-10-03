<?php

namespace Tickit\UserBundle\Tests\Listener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Tests\Fixtures\KernelForTest;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\UserBundle\Listener\KernelExceptionListener;

/**
 * KernelExceptionListener tests
 *
 * @package Tickit\UserBundle\Tests\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class KernelExceptionListenerTest extends AbstractFunctionalTest
{
    /**
     * Tests the onKernelException() method
     *
     * @return void
     */
    public function testOnKernelExceptionDoesNotInterceptNonAjaxRequest()
    {
        $event = $this->getEvent();
        $listener = new KernelExceptionListener();
        $listener->onKernelException($event);

        $this->assertEquals(200, $event->getResponse()->getStatusCode());
        $this->assertEquals('content', $event->getResponse()->getContent());
    }

    /**
     * Tests the onKernelException() method
     *
     * @return void
     */
    public function testOnKernelExceptionDoesNotInterceptNonAccessDeniedException()
    {
        $event = $this->getEvent(new \InvalidArgumentException());
        $listener = new KernelExceptionListener();
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
        $listener = new KernelExceptionListener();
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
        $response = new Response('content');

        if (null === $exception) {
            $exception = new AccessDeniedException();
        }

        $event = new GetResponseForExceptionEvent($kernel, $request, 1, $exception);
        $event->setResponse($response);

        return $event;
    }
}

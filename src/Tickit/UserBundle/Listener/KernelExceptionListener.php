<?php

namespace Tickit\UserBundle\Listener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Kernel exception listener.
 *
 * Handles exceptions thrown within the kernel.
 *
 * @package Tickit\UserBundle\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class KernelExceptionListener
{
    /**
     * Handles a kernel exception.
     *
     * If the request is an XmlHttpRequest and an AccessDeniedException has been thrown then
     * this method will set the response to a 403 rather than redirecting to /login.
     *
     * @param GetResponseForExceptionEvent $event The exception event
     *
     * @return void
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $request = $event->getRequest();
        $exception = $event->getException();

        if ($request->isXmlHttpRequest() && $exception instanceof AccessDeniedException) {
            $event->setResponse(new Response('', 403));
        }
    }
}

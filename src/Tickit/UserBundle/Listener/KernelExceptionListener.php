<?php

namespace Tickit\UserBundle\Listener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * File description
 *
 * @package Namespace\Class
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class KernelExceptionListener
{
    /**
     *
     *
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $event->setResponse(new Response('', 403));
    }
}

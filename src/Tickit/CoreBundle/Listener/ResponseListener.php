<?php

namespace Tickit\CoreBundle\Listener;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;

/**
 * Response listener.
 *
 * Modifies the response before it gets served to the client.
 *
 * @package Tickit\CoreBundle\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ResponseListener
{
    /**
     * Constructor.
     *
     * @param string $environment The application environment name
     */
    public function __construct($environment)
    {
        $this->environment = $environment;
    }

    /**
     * Handles the response
     *
     * @param FilterResponseEvent $event The event object
     */
    public function onResponse(FilterResponseEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $cookie = new Cookie('env', $this->environment);
        $event->getResponse()->headers->setCookie($cookie);
    }
}

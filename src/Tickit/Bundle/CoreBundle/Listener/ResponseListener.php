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

namespace Tickit\Bundle\CoreBundle\Listener;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;

/**
 * Response listener.
 *
 * Modifies the response before it gets served to the client.
 *
 * @package Tickit\Bundle\CoreBundle\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ResponseListener
{
    /**
     * The environment name
     *
     * @var string
     */
    private $environment;

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

        $cookie = new Cookie('env', $this->environment, 0, '/', null, false, false);
        $event->getResponse()->headers->setCookie($cookie);
    }
}

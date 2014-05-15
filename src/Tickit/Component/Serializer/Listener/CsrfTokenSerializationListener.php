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

namespace Tickit\Component\Serializer\Listener;

use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GenericSerializationVisitor;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Tickit\Component\Model\IdentifiableInterface;

/**
 * CSRF Token Serialization Listener.
 *
 * Adds a CSRF token to serialized models
 *
 * @package Tickit\Component\Serializer\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class CsrfTokenSerializationListener
{
    /**
     * A CSRF token manager
     *
     * @var CsrfTokenManagerInterface
     */
    private $tokenManager;

    /**
     * Constructor.
     *
     * @param CsrfTokenManagerInterface $tokenManager A CSRF token manager
     */
    public function __construct(CsrfTokenManagerInterface $tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }

    /**
     * Post serialize event handler.
     *
     * Adds a CSRF token to the serialized output.
     *
     * @param ObjectEvent $event The event object
     */
    public function onPostSerialize(ObjectEvent $event)
    {
        $visitor = $event->getVisitor();
        if ($visitor instanceof GenericSerializationVisitor) {
            $object = $event->getObject();
            if ($object instanceof IdentifiableInterface) {
                $token = $this->tokenManager->getToken(get_class($object) . $object->getId());
                $visitor->addData('csrfToken', $token->getValue());
            }
        }
    }
}

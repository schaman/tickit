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

namespace Tickit\Component\Controller\Helper;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Tickit\Component\Decorator\Collection\DomainObjectCollectionDecoratorInterface;
use Tickit\Component\Decorator\DomainObjectDecoratorInterface;
use Tickit\Component\Model\User\User;
use Tickit\Component\Serializer\SerializerInterface;

/**
 * Base controller helper.
 *
 * Provides helper methods for base controller functionality.
 *
 * @package Tickit\Component\Controller\Helper
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class BaseHelper
{
    /**
     * The current request
     *
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * A security context
     *
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * A domain object decorator
     *
     * @var DomainObjectDecoratorInterface
     */
    protected $objectDecorator;

    /**
     * A domain object collection decorator
     *
     * @var DomainObjectCollectionDecoratorInterface
     */
    protected $objectCollectionDecorator;

    /**
     * A router
     *
     * @var RouterInterface
     */
    protected $router;

    /**
     * An object serializer
     *
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * Constructor.
     *
     * @param RequestStack                             $requestStack              The request stack
     * @param SecurityContextInterface                 $securityContext           The security context
     * @param DomainObjectDecoratorInterface           $objectDecorator           An object decorator
     * @param DomainObjectCollectionDecoratorInterface $objectCollectionDecorator An object collection decorator
     * @param RouterInterface                          $router                    A router
     * @param SerializerInterface                      $serializer                An object serializer
     */
    public function __construct(
        RequestStack $requestStack,
        SecurityContextInterface $securityContext,
        DomainObjectDecoratorInterface $objectDecorator,
        DomainObjectCollectionDecoratorInterface $objectCollectionDecorator,
        RouterInterface $router,
        SerializerInterface $serializer
    ) {
        $this->requestStack = $requestStack;
        $this->securityContext = $securityContext;
        $this->objectDecorator = $objectDecorator;
        $this->objectCollectionDecorator = $objectCollectionDecorator;
        $this->router = $router;
        $this->serializer = $serializer;
    }

    /**
     * Gets the domain object decorator
     *
     * @return DomainObjectDecoratorInterface
     */
    public function getObjectDecorator()
    {
        return $this->objectDecorator;
    }

    /**
     * Gets the domain object collection decorator
     *
     * @return DomainObjectCollectionDecoratorInterface
     */
    public function getObjectCollectionDecorator()
    {
        return $this->objectCollectionDecorator;
    }

    /**
     * Gets the request object
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }

    /**
     * Gets the router
     *
     * @return RouterInterface
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Generates a URL from a route name
     *
     * @param string $routeName  The route name
     * @param array  $parameters Route parameters (if any)
     *
     * @return string
     */
    public function generateUrl($routeName, array $parameters = array())
    {
        return $this->getRouter()->generate($routeName, $parameters);
    }

    /**
     * Gets the current user
     *
     * @return User
     */
    public function getUser()
    {
        $token = $this->securityContext->getToken();

        if (null === $token) {
            return null;
        }

        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return null;
        }

        return $user;
    }

    /**
     * Gets the object serializer
     *
     * @return SerializerInterface
     */
    public function getSerializer()
    {
        return $this->serializer;
    }
}

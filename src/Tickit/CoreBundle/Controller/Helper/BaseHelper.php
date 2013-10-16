<?php

namespace Tickit\CoreBundle\Controller\Helper;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Tickit\CoreBundle\Decorator\DomainObjectDecoratorInterface;
use Tickit\UserBundle\Entity\User;

/**
 * Base controller helper.
 *
 * Provides helper methods for base controller functionality.
 *
 * @package Tickit\CoreBundle\Controller\Helper
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class BaseHelper
{
    /**
     * The request
     *
     * @var Request
     */
    protected $request;

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
     * A router
     *
     * @var RouterInterface
     */
    protected $router;

    /**
     * Constructor.
     *
     * @param Request                        $request         The request
     * @param SecurityContextInterface       $securityContext The security context
     * @param DomainObjectDecoratorInterface $objectDecorator The object decorator
     * @param RouterInterface                $router          A router
     */
    public function __construct(
        Request $request,
        SecurityContextInterface $securityContext,
        DomainObjectDecoratorInterface $objectDecorator,
        RouterInterface $router
    ) {
        $this->request = $request;
        $this->securityContext = $securityContext;
        $this->objectDecorator = $objectDecorator;
        $this->router = $router;
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
     * Gets the request object
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
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
}

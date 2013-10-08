<?php

namespace Tickit\CoreBundle\Controller\Helper;

use Symfony\Component\HttpFoundation\Request;
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
     * The security context
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
     * Constructor.
     *
     * @param Request $request
     * @param SecurityContextInterface $securityContext
     * @param DomainObjectDecoratorInterface $objectDecorator
     */
    public function __construct(
        Request $request,
        SecurityContextInterface $securityContext,
        DomainObjectDecoratorInterface $objectDecorator
    ) {

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

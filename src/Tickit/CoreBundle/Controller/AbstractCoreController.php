<?php

namespace Tickit\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tickit\CoreBundle\Decorator\DomainObjectArrayDecorator;
use Tickit\UserBundle\Manager\UserManager;
use Tickit\UserBundle\Entity\User;
use Tickit\CacheBundle\Cache\CacheFactory;

/**
 * Core controller.
 *
 * Provides base methods for all extending controller classes in the application.
 *
 * @package Tickit\CoreBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractCoreController extends Controller
{
    /**
     * Returns an instance of the user manager
     *
     * @return UserManager
     */
    protected function getUserManager()
    {
        return $this->container->get('tickit_user.manager');
    }

    /**
     * Returns an instance of the currently logged in user. If no user is logged in
     * this function will return null
     *
     * @return User
     */
    protected function getCurrentUser()
    {
        $token = $this->get('security.context')->getToken();

        return (null !== $token) ? $token->getUser() : null;
    }

    /**
     * Gets the cache factory.
     *
     * Returns an instance of the caching factory which provides access to the different caching engines
     *
     * @return CacheFactory
     */
    protected function getCacheFactory()
    {
        return $this->get('tickit_cache.factory');
    }

    /**
     * Gets an array decorator
     *
     * @return DomainObjectArrayDecorator
     */
    protected function getArrayDecorator()
    {
        return $this->get('tickit.domain_object_array_decorator');
    }

    /**
     * Checks a CSRF token for validity.
     *
     * @param string $token  The CSRF token to check
     * @param string $intent The intent of the token
     *
     * @throws NotFoundHttpException If the token is not valid
     *
     * @return void
     */
    protected function checkCsrfToken($token, $intent)
    {
        $tokenProvider = $this->get('form.csrf_provider');

        if (!$tokenProvider->isCsrfTokenValid($intent, $token)) {
            throw $this->createNotFoundException('Invalid CSRF token');
        }
    }
}

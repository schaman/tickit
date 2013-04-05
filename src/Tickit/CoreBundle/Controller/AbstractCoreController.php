<?php

namespace Tickit\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
}
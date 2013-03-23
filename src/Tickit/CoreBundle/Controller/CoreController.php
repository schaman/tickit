<?php

namespace Tickit\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\UserBundle\Doctrine\UserManager;

/**
 * The core controller class provides base methods for all controller classes
 * in the application.
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class CoreController extends Controller
{

    /**
     * Returns an instance of the user manager provided by FOSUserBundle
     *
     * @return UserManager
     */
    protected function _getUserManager()
    {
        return $this->container->get('fos_user.user_manager');
    }

    /**
     * Returns an instance of the currently logged in user. If no user is logged in
     * this function will return null
     *
     * @return \Tickit\UserBundle\Entity\User
     */
    protected function _getCurrentUser()
    {
        $token = $this->get('security.context')->getToken();

        return (null !== $token) ? $token->getUser() : null;
    }

    /**
     * Returns an instance of the caching factory which provides access to the different
     * caching engines
     *
     * @return \Tickit\CacheBundle\Cache\Cache
     */
    protected function _getCacheFactory()
    {
        return $this->get('tickit_cache.factory');
    }

}
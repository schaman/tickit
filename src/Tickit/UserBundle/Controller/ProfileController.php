<?php

namespace Tickit\UserBundle\Controller;

use FOS\UserBundle\Controller\ProfileController as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Profile controller.
 *
 * Responsible for handling requests related to user profiles
 *
 * @package Tickit\UserBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProfileController extends BaseController
{
    /**
     * Redirects the user to the edit profile page.
     *
     * The show action is currently not needed for the application.
     *
     * @return RedirectResponse
     */
    public function showAction()
    {
        $router = $this->container->get('router');
        $editRoute = $router->generate('fos_user_profile_edit');

        return new RedirectResponse($editRoute);
    }

}

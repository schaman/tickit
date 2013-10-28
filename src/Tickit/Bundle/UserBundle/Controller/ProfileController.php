<?php

/*
 * Tickit, an open source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
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

namespace Tickit\Bundle\UserBundle\Controller;

use FOS\UserBundle\Controller\ProfileController as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Profile controller.
 *
 * Responsible for handling requests related to user profiles
 *
 * @package Tickit\Bundle\UserBundle\Controller
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

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

use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Controller\SecurityController as BaseController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Security controller.
 *
 * Overrides functionality provided by FOSUserBundle so that we can add custom logic to login related actions
 *
 * @package Tickit\Bundle\UserBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class SecurityController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    protected function renderLogin(array $data)
    {
        $engine = $this->container->getParameter('fos_user.template.engine');
        $template = sprintf('TickitUserBundle:Security:login.html.%s', $engine);

        return $this->container->get('templating')->renderResponse($template, $data);
    }
}

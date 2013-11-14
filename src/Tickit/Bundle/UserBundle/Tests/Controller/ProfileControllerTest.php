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

namespace Tickit\Bundle\UserBundle\Tests\Controller;

use Tickit\Component\Test\AbstractUnitTest;
use Tickit\Bundle\UserBundle\Controller\ProfileController;

/**
 * ProfileController tests
 *
 * @package Tickit\Bundle\UserBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProfileControllerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $container;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->container = $this->getMockContainer();
    }

    /**
     * Tests the showAction()
     */
    public function testShowActionRedirectsToEditAction()
    {
        $router = $this->getMockRouter();

        $this->container->expects($this->once())
                        ->method('get')
                        ->with('router')
                        ->will($this->returnValue($router));

        $router->expects($this->once())
               ->method('generate')
               ->with('fos_user_profile_edit')
               ->will($this->returnValue('/profile/url'));

        $response = $this->getController()->showAction();
        $this->assertInstanceOf('\Symfony\Component\HttpFoundation\RedirectResponse', $response);
    }

    /**
     * Gets a controller instance
     */
    private function getController()
    {
        $controller = new ProfileController();
        $controller->setContainer($this->container);

        return $controller;
    }
}

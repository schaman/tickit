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

use Symfony\Component\HttpFoundation\Response;
use Tickit\Bundle\CoreBundle\Tests\AbstractUnitTest;
use Tickit\Bundle\UserBundle\Controller\SecurityController;

/**
 * SecurityController tests
 *
 * @package Tickit\Bundle\UserBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class SecurityControllerTest extends AbstractUnitTest
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
     * Tests the renderLogin() method
     */
    public function testRenderLoginUsesCorrectTemplate()
    {
        $data = [];

        $this->container->expects($this->once())
                        ->method('getParameter')
                        ->with('fos_user.template.engine')
                        ->will($this->returnValue('twig'));

        $templating = $this->getMockTemplateEngine();
        $templating->expects($this->once())
                   ->method('renderResponse')
                   ->with('TickitUserBundle:Security:login.html.twig', $data)
                   ->will($this->returnValue(new Response('login content')));

        $this->container->expects($this->once())
                        ->method('get')
                        ->with('templating')
                        ->will($this->returnValue($templating));

        $controller = new \ReflectionClass($this->getController());
        $method = $controller->getMethod('renderLogin');
        $method->setAccessible(true);
        $response = $method->invoke($this->getController(), $data);

        $this->assertEquals('login content', $response->getContent());
    }

    /**
     * Gets a new controller instance
     *
     * @return SecurityController
     */
    private function getController()
    {
        $controller = new SecurityController();
        $controller->setContainer($this->container);

        return $controller;
    }
}

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

namespace Tickit\UserBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Tickit\Bundle\CoreBundle\Tests\AbstractUnitTest;
use Tickit\UserBundle\Controller\TemplateController;
use Tickit\UserBundle\Entity\User;

/**
 * TemplateController tests
 *
 * @package Tickit\UserBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TemplateControllerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $userManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $formHelper;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->userManager = $this->getMockBuilder('\Tickit\UserBundle\Manager\UserManager')
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $this->formHelper = $this->getMockFormHelper();
    }
    
    /**
     * Tests the createFormAction() method
     */
    public function testCreateFormActionBuildsCorrectResponse()
    {
        $user = new User();

        $this->userManager->expects($this->once())
                          ->method('createUser')
                          ->will($this->returnValue($user));

        $form = $this->getMockForm();
        $this->formHelper->expects($this->once())
                         ->method('createForm')
                         ->with('tickit_user', $user)
                         ->will($this->returnValue($form));

        $this->formHelper->expects($this->once())
                         ->method('renderForm')
                         ->with('TickitUserBundle:User:create.html.twig', $form)
                         ->will($this->returnValue(new Response('form content')));

        $response = $this->getController()->createFormAction();
        $this->assertEquals('form content', $response->getContent());
    }

    /**
     * Tests the editFormAction() method
     */
    public function testEditFormActionBuildsCorrectResponse()
    {
        $user = new User();

        $form = $this->getMockForm();
        $this->formHelper->expects($this->once())
                         ->method('createForm')
                         ->with('tickit_user', $user)
                         ->will($this->returnValue($form));

        $this->formHelper->expects($this->once())
                         ->method('renderForm')
                         ->with('TickitUserBundle:User:edit.html.twig', $form)
                         ->will($this->returnValue(new Response('form content')));

        $response = $this->getController()->editFormAction($user);
        $this->assertEquals('form content', $response->getContent());
    }

    /**
     * Gets a new controller instance
     *
     * @return TemplateController
     */
    private function getController()
    {
        return new TemplateController($this->userManager, $this->formHelper);
    }
}

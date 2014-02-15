<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tickit\Component\Test\AbstractUnitTest;
use Tickit\Bundle\UserBundle\Controller\UserController;
use Tickit\Component\Model\User\User;

/**
 * UserController tests
 *
 * @package Tickit\Bundle\UserBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserControllerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $csrfHelper;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $formHelper;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $baseHelper;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $userManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $passwordUpdater;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->csrfHelper = $this->getMockCsrfHelper();
        $this->formHelper = $this->getMockFormHelper();
        $this->baseHelper = $this->getMockBaseHelper();

        $this->userManager = $this->getMockBuilder('\Tickit\Component\Entity\Manager\UserManager')
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $this->passwordUpdater = $this->getMockBuilder('\Tickit\Bundle\UserBundle\Form\Password\UserPasswordUpdater')
                                      ->getMock();
    }
    
    /**
     * Tests the createAction() method
     */
    public function testCreateActionCreatesUserForValidForm()
    {
        $request = new Request();
        $form = $this->getMockForm();
        $user = new User();

        $this->userManager->expects($this->once())
                          ->method('createUser')
                          ->will($this->returnValue($user));

        $this->trainFormHelperToCreateForm($user, $form);
        $this->trainBaseHelperToReturnRequest($request);
        $this->trainFormToHandleRequest($form, $request);

        $this->trainFormToBeValid($form);
        $this->trainFormToReturnUser($form, $user);
        $this->userManager->expects($this->once())
                          ->method('create')
                          ->with($user);

        $this->baseHelper->expects($this->once())
                         ->method('generateUrl')
                         ->with('user_index')
                         ->will($this->returnValue('user_route'));

        $expectedData = ['success' => true, 'returnUrl' => 'user_route'];
        $response = $this->getController()->createAction();
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Tests the createAction() method
     */
    public function testCreateActionHandlesInvalidForm()
    {
        $request = new Request();
        $form = $this->getMockForm();
        $user = new User();
        $user->setUsername('test');

        $this->userManager->expects($this->once())
                          ->method('createUser')
                          ->will($this->returnValue($user));

        $this->trainFormHelperToCreateForm($user, $form);
        $this->trainBaseHelperToReturnRequest($request);
        $this->trainFormToHandleRequest($form, $request);
        $this->trainFormToBeInvalid($form);

        $this->formHelper->expects($this->once())
                         ->method('renderForm')
                         ->with('TickitUserBundle:User:create.html.twig', $form)
                         ->will($this->returnValue(new Response('form content')));

        $expectedData = ['success' => false, 'form' => 'form content'];
        $response = $this->getController()->createAction();
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Tests the updateAction() method
     */
    public function testUpdateActionUpdatesUserForValidForm()
    {
        $user = new User();
        $user->setUsername('test');
        $form = $this->getMockForm();
        $request = new Request();

        $this->trainFormHelperToCreateForm($user, $form);
        $this->trainBaseHelperToReturnRequest($request);
        $this->trainFormToHandleRequest($form, $request);
        $this->trainFormToBeValid($form);
        $this->trainFormToReturnUser($form, $user);

        $this->passwordUpdater->expects($this->once())
                              ->method('updatePassword')
                              ->with($user, $user)
                              ->will($this->returnValue($user));

        $this->userManager->expects($this->once())
                          ->method('update')
                          ->with($user);

        $expectedData = ['success' => true];
        $response = $this->getController()->editAction($user);
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Tests the updateAction() method
     */
    public function testUpdateActionHandlesInvalidForm()
    {
        $user = new User();
        $user->setUsername('test');
        $form = $this->getMockForm();
        $request = new Request();

        $this->trainFormHelperToCreateForm($user, $form);
        $this->trainBaseHelperToReturnRequest($request);
        $this->trainFormToHandleRequest($form, $request);
        $this->trainFormToBeInvalid($form);

        $this->formHelper->expects($this->once())
                         ->method('renderForm')
                         ->with('TickitUserBundle:User:edit.html.twig', $form)
                         ->will($this->returnValue(new Response('form content')));

        $expectedData = ['success' => false, 'form' => 'form content'];
        $response = $this->getController()->editAction($user);
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Tests the deleteAction() method
     */
    public function testDeleteActionDeletesUser()
    {
        $request = new Request(['token' => 'token value']);
        $this->trainBaseHelperToReturnRequest($request);
        $this->csrfHelper->expects($this->once())
                         ->method('checkCsrfToken')
                         ->with('token value', UserController::CSRF_DELETE_INTENTION);

        $user = new User();
        $this->userManager->expects($this->once())
                          ->method('deleteUser')
                          ->with($user);

        $expectedData = ['success' => true];
        $response = $this->getController()->deleteAction($user);
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Gets a new controller instance
     *
     * @return UserController
     */
    private function getController()
    {
        return new UserController(
            $this->csrfHelper,
            $this->formHelper,
            $this->baseHelper,
            $this->userManager,
            $this->passwordUpdater
        );
    }

    private function trainFormHelperToCreateForm($data, \PHPUnit_Framework_MockObject_MockObject $form)
    {
        $this->formHelper->expects($this->once())
                         ->method('createForm')
                         ->with('tickit_user', $data)
                         ->will($this->returnValue($form));
    }

    private function trainBaseHelperToReturnRequest(Request $request)
    {
        $this->baseHelper->expects($this->once())
                         ->method('getRequest')
                         ->will($this->returnValue($request));
    }

    private function trainFormToHandleRequest(\PHPUnit_Framework_MockObject_MockObject $form, Request $request)
    {
        $form->expects($this->once())
             ->method('handleRequest')
             ->with($request);
    }

    private function trainFormToBeValid(\PHPUnit_Framework_MockObject_MockObject $form)
    {
        $form->expects($this->once())
             ->method('isValid')
             ->will($this->returnValue(true));
    }

    private function trainFormToBeInvalid(\PHPUnit_Framework_MockObject_MockObject $form)
    {
        $form->expects($this->once())
             ->method('isValid')
             ->will($this->returnValue(false));
    }

    private function trainFormToReturnUser(\PHPUnit_Framework_MockObject_MockObject $form, User $user)
    {
        $form->expects($this->once())
             ->method('getData')
             ->will($this->returnValue($user));
    }
}

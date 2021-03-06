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

namespace Tickit\Bundle\ProjectBundle\Tests\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tickit\Component\Test\AbstractUnitTest;
use Tickit\Bundle\ProjectBundle\Controller\ProjectController;
use Tickit\Component\Model\Project\ChoiceAttributeValue;
use Tickit\Component\Model\Project\LiteralAttributeValue;
use Tickit\Component\Model\Project\Project;

/**
 * ProjectController tests
 *
 * @package Tickit\Bundle\ProjectBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectControllerTest extends AbstractUnitTest
{
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
    private $attributeManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $projectManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $projectFormType;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $csrfHelper;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->formHelper = $this->getMockFormHelper();
        $this->baseHelper = $this->getMockBaseHelper();
        $this->csrfHelper = $this->getMockCsrfHelper();

        $this->attributeManager = $this->getMockBuilder('\Tickit\Component\Entity\Manager\Project\AttributeManager')
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $this->projectFormType = $this->getMockBuilder('\Tickit\Bundle\ProjectBundle\Form\Type\ProjectFormType')
                                      ->disableOriginalConstructor()
                                      ->getMock();

        $this->projectManager = $this->getMockBuilder('\Tickit\Component\Entity\Manager\Project\ProjectManager')
                                     ->disableOriginalConstructor()
                                     ->getMock();
    }
    
    /**
     * Tests the createAction() method
     */
    public function testCreateActionCreatesProjectForValidForm()
    {
        $project = new Project();
        $attributes = new ArrayCollection(array(new LiteralAttributeValue(), new ChoiceAttributeValue()));
        $project->setAttributes($attributes);

        $this->attributeManager->expects($this->once())
                               ->method('getAttributeValuesForProject')
                               ->will($this->returnValue($attributes));

        $form = $this->getMockForm();
        $this->formHelper->expects($this->once())
                         ->method('createForm')
                         ->with($this->projectFormType, $project)
                         ->will($this->returnValue($form));

        $request = new Request();
        $this->trainBaseHelperToReturnRequest($request);
        $this->trainFormToHandleRequest($form, $request);
        $this->trainFormToBeValid($form);
        $this->trainFormToReturnProject($form, $project);

        $this->projectManager->expects($this->once())
                             ->method('create')
                             ->with($project);

        $this->baseHelper->expects($this->once())
                         ->method('generateUrl')
                         ->with('project_index')
                         ->will($this->returnValue('redirect-url'));

        $expectedData = ['success' => true, 'returnUrl' => 'redirect-url'];
        $response = $this->getController()->createAction();
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Tests the createAction() method
     */
    public function testCreateActionHandlesInvalidForm()
    {
        $project = new Project();
        $attributes = new ArrayCollection(array(new LiteralAttributeValue(), new ChoiceAttributeValue()));
        $project->setAttributes($attributes);

        $this->attributeManager->expects($this->once())
                               ->method('getAttributeValuesForProject')
                               ->will($this->returnValue($attributes));

        $form = $this->getMockForm();
        $this->formHelper->expects($this->once())
                         ->method('createForm')
                         ->with($this->projectFormType)
                         ->will($this->returnValue($form));

        $request = new Request();
        $this->trainBaseHelperToReturnRequest($request);
        $this->trainFormToHandleRequest($form, $request);
        $this->trainFormToBeInvalid($form);

        $this->formHelper->expects($this->once())
                         ->method('renderForm')
                         ->with('TickitProjectBundle:Project:create.html.twig', $form)
                         ->will($this->returnValue(new Response('form-content')));

        $expectedData = ['success' => false, 'form' => 'form-content'];
        $response = $this->getController()->createAction();
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Tests the editAction() method
     */
    public function testEditActionUpdatesProjectForValidForm()
    {
        $project = new Project();
        $project->setName('test');

        $form = $this->getMockForm();
        $this->formHelper->expects($this->once())
                         ->method('createForm')
                         ->with($this->projectFormType, $project)
                         ->will($this->returnValue($form));

        $request = new Request();
        $this->trainBaseHelperToReturnRequest($request);
        $this->trainFormToHandleRequest($form, $request);
        $this->trainFormToBeValid($form);
        $this->trainFormToReturnProject($form, $project);

        $this->projectManager->expects($this->once())
                             ->method('update')
                             ->with($project);

        $expectedData = ['success' => true];
        $response = $this->getController()->editAction($project);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Tests the editAction() method
     */
    public function testEditActionHandlesInvalidForm()
    {
        $project = new Project();
        $project->setName('test');

        $form = $this->getMockForm();
        $this->formHelper->expects($this->once())
                         ->method('createForm')
                         ->with($this->projectFormType, $project)
                         ->will($this->returnValue($form));

        $request = new Request();
        $this->trainBaseHelperToReturnRequest($request);
        $this->trainFormToHandleRequest($form, $request);
        $this->trainFormToBeInvalid($form);

        $this->formHelper->expects($this->once())
                         ->method('renderForm')
                         ->with('TickitProjectBundle:Project:edit.html.twig', $form)
                         ->will($this->returnValue(new Response('form-content')));

        $expectedData = ['success' => false, 'form' => 'form-content'];
        $response = $this->getController()->editAction($project);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Tests the deleteAction() method
     */
    public function testDeleteActionDeletesProject()
    {
        $project = new Project();
        $request = new Request(['token' => 'token-value']);

        $this->trainBaseHelperToReturnRequest($request);

        $this->csrfHelper->expects($this->once())
                         ->method('checkCsrfToken')
                         ->with('token-value', ProjectController::CSRF_DELETE_INTENTION);

        $this->projectManager->expects($this->once())
                             ->method('delete')
                             ->with($project);

        $expectedData = ['success' => true];
        $response = $this->getController()->deleteAction($project);
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Gets a controller instance
     *
     * @return ProjectController
     */
    private function getController()
    {
        return new ProjectController(
            $this->formHelper,
            $this->baseHelper,
            $this->attributeManager,
            $this->projectManager,
            $this->projectFormType,
            $this->csrfHelper
        );
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

    private function trainFormToBeInvalid(\PHPUnit_Framework_MockObject_MockObject $form)
    {
        $form->expects($this->once())
             ->method('isValid')
             ->will($this->returnValue(false));
    }

    private function trainFormToBeValid(\PHPUnit_Framework_MockObject_MockObject $form)
    {
        $form->expects($this->once())
             ->method('isValid')
             ->will($this->returnValue(true));
    }

    private function trainFormToReturnProject(\PHPUnit_Framework_MockObject_MockObject $form, Project $project)
    {
        $form->expects($this->once())
             ->method('getData')
             ->will($this->returnValue($project));
    }
}

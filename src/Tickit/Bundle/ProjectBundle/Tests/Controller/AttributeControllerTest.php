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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tickit\Component\Test\AbstractUnitTest;
use Tickit\Bundle\ProjectBundle\Controller\AttributeController;
use Tickit\Component\Model\Project\AbstractAttribute;
use Tickit\Component\Model\Project\LiteralAttribute;
use Tickit\Bundle\ProjectBundle\Form\Type\LiteralAttributeFormType;

/**
 * AttributeController tests
 *
 * @package Tickit\Bundle\ProjectBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AttributeControllerTest extends AbstractUnitTest
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
    private $formTypeGuesser;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $attributeManager;

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

        $this->formTypeGuesser = $this->getMockBuilder('Tickit\Bundle\ProjectBundle\Form\Guesser\AttributeFormTypeGuesser')
                                      ->disableOriginalConstructor()
                                      ->getMock();

        $this->attributeManager = $this->getMockBuilder('Tickit\Component\Entity\Manager\AttributeManager')
                                       ->disableOriginalConstructor()
                                       ->getMock();
    }
    
    /**
     * Tests the createAction() method
     *
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testCreateActionThrowsExceptionForInvalidType()
    {
        $this->getController()->createAction('invalid type');
    }

    /**
     * Tests the createAction() method
     */
    public function testCreateActionCreatesAttributeForValidForm()
    {
        $attribute = new LiteralAttribute();
        $formType = new LiteralAttributeFormType();

        $this->formTypeGuesser->expects($this->once())
                              ->method('guessByAttributeType')
                              ->with($attribute->getType())
                              ->will($this->returnValue($formType));

        $form = $this->getMockForm();

        $this->formHelper->expects($this->once())
                         ->method('createForm')
                         ->with($formType, $attribute)
                         ->will($this->returnValue($form));

        $request = new Request();
        $this->trainBaseHelperToReturnRequest($request);
        $this->trainFormToHandleRequest($form, $request);
        $this->trainFormToBeValid($form);
        $this->trainFormToReturnAttribute($form, $attribute);

        $this->attributeManager->expects($this->once())
                               ->method('create')
                               ->with($attribute);

        $this->baseHelper->expects($this->once())
                         ->method('generateUrl')
                         ->with('project_attribute_index')
                         ->will($this->returnValue('redirect-url'));

        $expectedData = [
            'success' => true,
            'returnUrl' => 'redirect-url'
        ];
        $response = $this->getController()->createAction($attribute->getType());
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Tests the createAction() method
     */
    public function testCreateActionHandlesInvalidForm()
    {
        $attribute = new LiteralAttribute();
        $formType = new LiteralAttributeFormType();

        $this->formTypeGuesser->expects($this->once())
                              ->method('guessByAttributeType')
                              ->with($attribute->getType())
                              ->will($this->returnValue($formType));

        $form = $this->getMockForm();

        $this->formHelper->expects($this->once())
                         ->method('createForm')
                         ->with($formType, $attribute)
                         ->will($this->returnValue($form));

        $request = new Request();
        $this->trainBaseHelperToReturnRequest($request);
        $this->trainFormToHandleRequest($form, $request);
        $this->trainFormToBeInvalid($form);

        $this->formHelper->expects($this->once())
                         ->method('renderForm')
                         ->with('TickitProjectBundle:Attribute:create.html.twig', $form, ['type' => $attribute->getType()])
                         ->will($this->returnValue(new Response('form-content')));

        $expectedData = [
            'success' => false,
            'form' => 'form-content'
        ];
        $response = $this->getController()->createAction($attribute->getType());
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Tests the editAction method()
     */
    public function testEditActionUpdatesAttributeFormValidForm()
    {
        $attribute = new LiteralAttribute();
        $formType = new LiteralAttributeFormType();

        $this->formTypeGuesser->expects($this->once())
                              ->method('guessByAttributeType')
                              ->with($attribute->getType())
                              ->will($this->returnValue($formType));

        $form = $this->getMockForm();

        $this->formHelper->expects($this->once())
                         ->method('createForm')
                         ->with($formType, $attribute)
                         ->will($this->returnValue($form));

        $request = new Request();
        $this->trainBaseHelperToReturnRequest($request);
        $this->trainFormToHandleRequest($form, $request);

        $this->trainFormToBeValid($form);

        $this->attributeManager->expects($this->once())
                               ->method('update')
                               ->with($attribute);

        $expectedData = ['success' => true];
        $response = $this->getController()->editAction($attribute);
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Tests the editAction() method
     */
    public function testEditActionHandlesInvalidForm()
    {
        $attribute = new LiteralAttribute();
        $formType = new LiteralAttributeFormType();

        $this->formTypeGuesser->expects($this->once())
                              ->method('guessByAttributeType')
                              ->with($attribute->getType())
                              ->will($this->returnValue($formType));

        $form = $this->getMockForm();

        $this->formHelper->expects($this->once())
                         ->method('createForm')
                         ->with($formType, $attribute)
                         ->will($this->returnValue($form));

        $request = new Request();
        $this->trainBaseHelperToReturnRequest($request);
        $this->trainFormToHandleRequest($form, $request);

        $this->trainFormToBeInvalid($form);

        $this->formHelper->expects($this->once())
                         ->method('renderForm')
                         ->with(
                             'TickitProjectBundle:Attribute:edit.html.twig',
                             $form,
                             ['type' => $attribute->getType()]
                         )
                         ->will($this->returnValue(new Response('form-content')));

        $expectedData = ['success' => false, 'form' => 'form-content'];
        $response = $this->getController()->editAction($attribute);
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Tests the deleteAction() method
     */
    public function testDeleteActionDeletesAttribute()
    {
        $attribute = new LiteralAttribute();
        $request = new Request(['token' => 'token-value']);
        $this->trainBaseHelperToReturnRequest($request);

        $this->csrfHelper->expects($this->once())
                         ->method('checkCsrfToken')
                         ->with('token-value', AttributeController::CSRF_DELETE_INTENTION);

        $this->attributeManager->expects($this->once())
                               ->method('delete')
                               ->with($attribute);

        $expectedData = ['success' => true];
        $response = $this->getController()->deleteAction($attribute);
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Gets a controller instance
     *
     * @return AttributeController
     */
    private function getController()
    {
        return new AttributeController(
            $this->formHelper,
            $this->baseHelper,
            $this->formTypeGuesser,
            $this->attributeManager,
            $this->csrfHelper
        );
    }

    private function trainBaseHelperToReturnRequest(Request $request)
    {
        $this->baseHelper->expects($this->once())
                         ->method('getRequest')
                         ->will($this->returnValue($request));
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

    private function trainFormToHandleRequest(\PHPUnit_Framework_MockObject_MockObject $form, Request $request)
    {
        $form->expects($this->once())
             ->method('handleRequest')
             ->with($request);
    }

    private function trainFormToReturnAttribute(\PHPUnit_Framework_MockObject_MockObject $form, AbstractAttribute $attribute)
    {
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($attribute));
    }
}

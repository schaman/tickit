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

namespace Tickit\Bundle\IssueBundle\Tests\Controller;

use MyProject\Proxies\__CG__\stdClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tickit\Bundle\IssueBundle\Controller\IssueController;
use Tickit\Component\Model\Issue\Issue;
use Tickit\Component\Test\AbstractUnitTest;

/**
 * IssueController tests
 *
 * @package Tickit\Bundle\IssueBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueControllerTest extends AbstractUnitTest
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
    private $issueManager;

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
        $this->issueManager = $this->getMockBuilder('\Tickit\Component\Entity\Manager\IssueManager')
                                   ->disableOriginalConstructor()
                                   ->getMock();
    }

    /**
     * Tests the createAction() method
     *
     * @dataProvider getCreateActionFixtures
     */
    public function testCreateAction($formIsValid)
    {
        $request = new Request();
        $form = $this->getMockForm();

        $this->trainBaseHelperToReturnRequest($request);
        $this->trainFormHelperToCreateForm('tickit_issue', new Issue(), $form);
        $this->trainFormToHandleRequest($form, $request);

        if (true === $formIsValid) {
            $this->trainFormToBeValid($form);
            $formData = new Issue();
            $this->trainFormToReturnIssue($form, $formData);

            $this->issueManager->expects($this->once())
                               ->method('create')
                               ->with($formData);
            $expectedData = ['success' => true];
        } else {
            $fakeFormContent = 'form content';
            $this->trainFormToBeInvalid($form);
            $this->trainFormHelperToCreateFormView($form, 'TickitIssueBundle:Issue:create.html.twig', $fakeFormContent);
            $expectedData = ['success' => false, 'form' => $fakeFormContent];
        }

        /** @var JsonResponse $response */
        $response = $this->getController()->createAction();
        $this->assertInstanceOf('\Symfony\Component\HttpFoundation\JsonResponse', $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    public function getCreateActionFixtures()
    {
        return [
            [true],
            [false]
        ];
    }

    /**
     * Gets a new controller instance
     *
     * @return IssueController
     */
    private function getController()
    {
        return new IssueController(
            $this->formHelper,
            $this->baseHelper,
            $this->issueManager,
            $this->csrfHelper
        );
    }

    private function trainBaseHelperToReturnRequest(Request $request)
    {
        $this->baseHelper->expects($this->once())
                         ->method('getRequest')
                         ->will($this->returnValue($request));
    }

    private function trainFormHelperToCreateForm($type, $data, $returnedForm)
    {
        $this->formHelper->expects($this->once())
                         ->method('createForm')
                         ->with($type, $data)
                         ->will($this->returnValue($returnedForm));
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

    private function trainFormToReturnIssue(\PHPUnit_Framework_MockObject_MockObject $form, Issue $issue)
    {
        $form->expects($this->once())
             ->method('getData')
             ->will($this->returnValue($issue));

    }

    private function trainFormHelperToCreateFormView(\PHPUnit_Framework_MockObject_MockObject $form, $template, $viewContent)
    {
        $this->formHelper->expects($this->once())
                         ->method('renderForm')
                         ->with($template, $form)
                         ->will($this->returnValue(new Response($viewContent)));
    }
}

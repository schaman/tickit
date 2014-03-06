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

use Tickit\Bundle\IssueBundle\Controller\TemplateController;
use Tickit\Component\Test\AbstractUnitTest;

/**
 * TemplateController tests
 *
 * @package Tickit\Bundle\IssueBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TemplateControllerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $formHelper;

    /**
     * Setup
     */
    public function setUp()
    {
        $this->formHelper = $this->getMockFormHelper();
    }

    /**
     * Tests the filterFormAction() method
     */
    public function testFilterFormAction()
    {
        $form = $this->getMockForm();

        $this->formHelper->expects($this->once())
                         ->method('createForm')
                         ->with('tickit_issue_filters')
                         ->will($this->returnValue($form));

        $formView = 'form view content';
        $this->formHelper->expects($this->once())
                         ->method('renderForm')
                         ->with('TickitIssueBundle:Filter:filter-form.html.twig', $form)
                         ->will($this->returnValue($formView));

        $response = $this->getController()->filterFormAction();

        $this->assertEquals($formView, $response);
    }

    /**
     * Gets a new controller instance
     *
     * @return TemplateController
     */
    private function getController()
    {
        return new TemplateController($this->formHelper);
    }
}

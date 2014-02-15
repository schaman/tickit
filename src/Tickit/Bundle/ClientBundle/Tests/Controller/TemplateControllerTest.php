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

namespace Tickit\Bundle\ClientBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Tickit\Bundle\ClientBundle\Controller\TemplateController;
use Tickit\Bundle\ClientBundle\Form\Type\FilterFormType;
use Tickit\Component\Model\Client\Client;
use Tickit\Component\Test\AbstractUnitTest;

/**
 * TemplateController tests
 *
 * @package Tickit\Bundle\ClientBundle\Tests\Controller
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
    protected function setUp()
    {
        $this->formHelper = $this->getMockFormHelper();
    }
    
    /**
     * Tests the createClientFormAction() method
     */
    public function testCreateClientFormActionBuildsCorrectResponse()
    {
        $client = new Client();

        $form = $this->getMockForm();
        $this->trainFormHelperToCreateForm($client, $form);
        $this->trainFormHelperToRenderForm($form, 'TickitClientBundle:Client:create.html.twig', 'create-form-template');

        $this->assertEquals('create-form-template', $this->getController()->createClientFormAction()->getContent());
    }

    /**
     * Tests the editClientFormAction() method
     */
    public function testEditClientFormActionBuildsCorrectResponse()
    {
        $client = new Client();
        $client->setId(1);

        $form = $this->getMockForm();
        $this->trainFormHelperToCreateForm($client, $form);
        $this->trainFormHelperToRenderForm($form, 'TickitClientBundle:Client:edit.html.twig', 'edit-form-template');

        $this->assertEquals('edit-form-template', $this->getController()->editClientFormAction($client)->getContent());
    }

    /**
     * Tests the filterFormAction() method
     */
    public function testFilterFormActionBuildsCorrectResponse()
    {
        $form = $this->getMockForm();
        $this->formHelper->expects($this->once())
                         ->method('createForm')
                         ->with('tickit_client_filters', [])
                         ->will($this->returnValue($form));

        $this->trainFormHelperToRenderForm($form, 'TickitClientBundle:Filters:filter-form.html.twig', 'content');

        $this->assertEquals('content', $this->getController()->filterFormAction()->getContent());
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

    private function trainFormHelperToCreateForm(Client $client, \PHPUnit_Framework_MockObject_MockObject $form)
    {
        $this->formHelper->expects($this->once())
             ->method('createForm')
             ->with('tickit_client', $client)
             ->will($this->returnValue($form));
    }

    private function trainFormHelperToRenderForm(\PHPUnit_Framework_MockObject_MockObject $form, $template, $returnText)
    {
        $this->formHelper->expects($this->once())
            ->method('renderForm')
            ->with($template, $form)
            ->will($this->returnValue(new Response($returnText)));
    }
}

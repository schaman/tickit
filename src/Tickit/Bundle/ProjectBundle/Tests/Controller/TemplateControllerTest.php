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
use Symfony\Component\HttpFoundation\Response;
use Tickit\Bundle\ProjectBundle\Form\Type\FilterFormType;
use Tickit\Component\Test\AbstractUnitTest;
use Tickit\Bundle\ProjectBundle\Controller\TemplateController;
use Tickit\Component\Model\Project\LiteralAttribute;
use Tickit\Component\Model\Project\LiteralAttributeValue;
use Tickit\Component\Model\Project\Project;
use Tickit\Bundle\ProjectBundle\Form\Type\LiteralAttributeFormType;

/**
 * TemplateController tests
 *
 * @package Tickit\Bundle\ProjectBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TemplateControllerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $attributeManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $formHelper;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $attributeFormTypeGuesser;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->attributeManager = $this->getMockBuilder('Tickit\Component\Entity\Manager\Project\AttributeManager')
                                       ->disableOriginalConstructor()
                                       ->getMock();

        $this->formHelper = $this->getMockFormHelper();

        $this->attributeFormTypeGuesser = $this->getMockBuilder('Tickit\Bundle\ProjectBundle\Form\Guesser\AttributeFormTypeGuesser')
                                               ->disableOriginalConstructor()
                                               ->getMock();
    }
    
    /**
     * Tests the createProjectFormAction() method
     */
    public function testCreateProjectFormActionBuildsCorrectResponse()
    {
        $attributes = new ArrayCollection([new LiteralAttributeValue()]);

        $this->attributeManager->expects($this->once())
                               ->method('getAttributeValuesForProject')
                               ->will($this->returnValue($attributes));

        $projectWithAttributes = new Project();
        $projectWithAttributes->setAttributes($attributes);

        $projectForm = $this->getMockForm();

        $this->formHelper->expects($this->once())
                         ->method('createForm')
                         ->with('tickit_project', $projectWithAttributes)
                         ->will($this->returnValue($projectForm));

        $this->formHelper->expects($this->once())
                         ->method('renderForm')
                         ->with('TickitProjectBundle:Project:create.html.twig', $projectForm);

        $this->getController()->createProjectFormAction();
    }

    /**
     * Tests the editProjectFormAction() method
     */
    public function testEditProjectFormActionBuildsCorrectResponse()
    {
        $project = new Project();
        $project->setName('test');

        $form = $this->getMockForm();

        $this->formHelper->expects($this->once())
                         ->method('createForm')
                         ->with('tickit_project', $project)
                         ->will($this->returnValue($form));

        $response = new Response();
        $this->formHelper->expects($this->once())
                         ->method('renderForm')
                         ->with('TickitProjectBundle:Project:edit.html.twig', $form)
                         ->will($this->returnValue($response));

        $return = $this->getController()->editProjectFormAction($project);
        $this->assertSame($response, $return);
    }

    /**
     * Tests the createProjectAttributeFormAction() method
     *
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testCreateProjectAttributeFormActionThrowsExceptionForInvalidType()
    {
        $this->getController()->createProjectAttributeFormAction('invalid type');
    }

    /**
     * Tests the createProjectAttributeFormAction() method
     */
    public function testCreateProjectAttributeFormActionBuildsCorrectResponse()
    {
        $formType = new LiteralAttributeFormType();
        $attribute = new LiteralAttribute();

        $this->attributeFormTypeGuesser->expects($this->once())
                                       ->method('guessByAttributeType')
                                       ->with($attribute->getType())
                                       ->will($this->returnValue($formType));

        $form = $this->getMockForm();

        $this->formHelper->expects($this->once())
                         ->method('createForm')
                         ->with($formType, $attribute)
                         ->will($this->returnValue($form));

        $response = new Response();
        $this->formHelper->expects($this->once())
                         ->method('renderForm')
                         ->with(
                             'TickitProjectBundle:Attribute:create.html.twig',
                             $form,
                             array('type' => $attribute->getType())
                         )
                         ->will($this->returnValue($response));

        $return = $this->getController()->createProjectAttributeFormAction($attribute->getType());
        $this->assertSame($response, $return);
    }

    /**
     * Tests the editProjectAttributeFormAction() method
     */
    public function testEditProjectAttributeFormActionBuildsCorrectResponse()
    {
        $formType = new LiteralAttributeFormType();
        $attribute = new LiteralAttribute();
        $attribute->setName('test');

        $this->attributeFormTypeGuesser->expects($this->once())
                                       ->method('guessByAttributeType')
                                       ->with($attribute->getType())
                                       ->will($this->returnValue($formType));

        $form = $this->getMockForm();
        $this->formHelper->expects($this->once())
                         ->method('createForm')
                         ->with($formType)
                         ->will($this->returnValue($form));

        $response = new Response();
        $this->formHelper->expects($this->once())
                         ->method('renderForm')
                         ->with(
                             'TickitProjectBundle:Attribute:edit.html.twig',
                             $form,
                             array('type' => $attribute->getType())
                         )
                         ->will($this->returnValue($response));

        $return = $this->getController()->editProjectAttributeFormAction($attribute);
        $this->assertSame($response, $return);
    }

    /**
     * Tests the filterFormAction() method
     */
    public function testFilterFormActionBuildsCorrectResponse()
    {
        $form = $this->getMockForm();
        $this->formHelper->expects($this->once())
                         ->method('createForm')
                         ->with('tickit_project_filters')
                         ->will($this->returnValue($form));

        $response = new Response();
        $this->formHelper->expects($this->once())
                         ->method('renderForm')
                         ->with(
                             'TickitProjectBundle:Filters:filter-form.html.twig',
                             $form
                         )
                         ->will($this->returnValue($response));

        $return = $this->getController()->filterFormAction();
        $this->assertSame($response, $return);
    }

    /**
     * Gets a controller instance
     *
     * @return TemplateController
     */
    private function getController()
    {
        return new TemplateController(
            $this->attributeManager,
            $this->formHelper,
            $this->attributeFormTypeGuesser
        );
    }
}

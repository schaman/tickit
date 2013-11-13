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

namespace Tickit\Bundle\ProjectBundle\Tests\Form\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\PreloadedExtension;
use Tickit\Bundle\ClientBundle\Entity\Client;
use Tickit\Bundle\ClientBundle\Form\Type\Picker\ClientPickerType;
use Tickit\Bundle\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;
use Tickit\Bundle\ProjectBundle\Entity\Project;
use Tickit\Bundle\ProjectBundle\Form\Type\ProjectFormType;

/**
 * ProjectFormType test
 *
 * @package Tickit\Bundle\ProjectBundle\Tests\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectFormTypeTest extends AbstractFormTypeTestCase
{
    /**
     * Set up
     */
    protected function setUp()
    {
        parent::setUp();

        $mockAttributeForm = $this->getMockBuilder('Tickit\Bundle\ProjectBundle\Form\Type\AttributeValueFormType')
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $this->formType = new ProjectFormType($mockAttributeForm);
    }

    /**
     * Tests the form submit
     *
     * @return void
     */
    public function testValidFormData()
    {
        $form = $this->factory->create($this->formType);

        $project = new Project();
        $project->setName(__FUNCTION__)
                ->setAttributes(new ArrayCollection())
                ->setTickets(new ArrayCollection())
                ->setCreatedAt(new \DateTime())
                ->setClient(new Client())
                ->setUpdatedAt(new \DateTime());

        $form->setData($project);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($project, $form->getData());

        $expectedViewComponents = array('name', 'attributes', 'client');
        $this->assertViewHasComponents($expectedViewComponents, $form->createView());
    }

    /**
     * @return array
     */
    protected function getExtensions()
    {
        $extensions = parent::getExtensions();

        $decorator = $this->getMockBuilder('Tickit\Component\Decorator\Entity\ClientEntityNameDecorator')
                          ->disableOriginalConstructor()
                          ->getMock();

        $transformer = $this->getMockBuilder(
            'Tickit\Bundle\ClientBundle\Form\Type\Picker\DataTransformer\ClientPickerDataTransformer'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $transformer->expects($this->any())
                    ->method('transform')
                    ->will($this->returnValue(1));

        $transformer->expects($this->any())
                    ->method('reverseTransform')
                    ->will($this->returnValue(new Client()));

        $decorator->expects($this->any())
                  ->method('convert')
                  ->will($this->returnValue('decorated client'));

        $clientPicker = new ClientPickerType($decorator, $transformer);

        $extensions[] = new PreloadedExtension([$clientPicker->getName() => $clientPicker], []);

        return $extensions;
    }
}

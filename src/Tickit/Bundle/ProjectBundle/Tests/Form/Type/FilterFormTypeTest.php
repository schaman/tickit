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

use Symfony\Component\Form\PreloadedExtension;
use Tickit\Bundle\ClientBundle\Form\Type\Picker\ClientPickerType;
use Tickit\Bundle\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;
use Tickit\Bundle\ProjectBundle\Form\Type\FilterFormType;
use Tickit\Bundle\UserBundle\Form\Type\Picker\UserPickerType;
use Tickit\Component\Model\Project\Project;

/**
 * FilterFormType tests
 *
 * @package Tickit\Bundle\ProjectBundle\Tests\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class FilterFormTypeTest extends AbstractFormTypeTestCase
{
    /**
     * Setup
     */
    protected function setUp()
    {
        parent::setUp();

        $this->formType = new FilterFormType();
    }

    /**
     * Tests the form submit
     */
    public function testSubmitValidData()
    {
        $form = $this->factory->create($this->formType);

        $data = [
            'name' => 'project-name',
            'owner' => '100,200',
            'status' => Project::STATUS_ARCHIVED,
            'client' => '120, 123'
        ];

        $form->submit($data);

        $expectedData = [
            'name' => 'project-name',
            'owner' => new \stdClass(),
            'status' => Project::STATUS_ARCHIVED,
            'client' => new \stdClass()
        ];

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expectedData, $form->getData());

        $expectedFields = ['name', 'owner', 'status', 'client'];
        $this->assertViewHasComponents($expectedFields, $form->createView());
    }

    /**
     * Gets form extensions
     *
     * @return array
     */
    protected function getExtensions()
    {
        $extensions = parent::getExtensions();

        $decorator = $this->getMockEntityDecorator();
        $transformer = $this->getMockPickerDataTransformer();

        $transformer->expects($this->any())
                    ->method('transform')
                    ->will($this->returnValue('transformed value'));

        $transformer->expects($this->any())
                    ->method('reverseTransform')
                    ->will($this->returnValue(new \stdClass()));

        $userPicker = new UserPickerType($decorator, $transformer);
        $clientPicker = new ClientPickerType($decorator, $transformer);

        $extensions[] = new PreloadedExtension(
            [$userPicker->getName() => $userPicker, $clientPicker->getName() => $clientPicker],
            []
        );

        return $extensions;
    }
}

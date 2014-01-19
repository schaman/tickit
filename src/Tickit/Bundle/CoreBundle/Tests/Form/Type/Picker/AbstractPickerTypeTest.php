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

namespace Tickit\Bundle\CoreBundle\Tests\Form\Type\Picker;

use Doctrine\Common\Collections\ArrayCollection;
use Tickit\Bundle\CoreBundle\Form\Type\Picker\AbstractPickerType;
use Tickit\Bundle\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;
use Tickit\Bundle\CoreBundle\Tests\Form\Type\Picker\Mock\MockEntity;

/**
 * AbstractPickerType tests
 *
 * @package Tickit\Bundle\CoreBundle\Tests\Form\Type\Picker
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AbstractPickerTypeTest extends AbstractFormTypeTestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $decorator;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $transformer;

    /**
     * Setup
     */
    protected function setUp()
    {
        parent::setUp();

        $this->transformer = $this->getMockForAbstractClass(
            '\Tickit\Bundle\CoreBundle\Form\Type\Picker\DataTransformer\AbstractPickerDataTransformer'
        );

        $this->transformer->expects($this->any())
                          ->method('getEntityIdentifier')
                          ->will($this->returnValue('id'));

        $this->formType = $this->getMockForAbstractClass(
            '\Tickit\Bundle\CoreBundle\Form\Type\Picker\AbstractPickerType',
            [$this->transformer]
        );
    }

    /**
     * Tests submission of a single entity ID
     */
    public function testSingleEntityIdSubmission()
    {
        $form = $this->factory->create(
            $this->formType,
            null,
            ['max_selections' => 1]
        );
        $entity = new MockEntity(1);

        $this->transformer->expects($this->once())
                          ->method('findEntityByIdentifier')
                          ->with(1)
                          ->will($this->returnValue($entity));

        $form->submit('1');

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals(1, $form->getConfig()->getAttribute('data-max-selections'));
        $this->assertEquals($entity, $form->getData());


    }

    /**
     * Tests form data of 2 entity IDs resolves to correct display names
     */
    public function testMultipleEntitySubmission()
    {
        $form = $this->factory->create($this->formType);

        $entity1 = new MockEntity(1);
        $entity2 = new MockEntity(2);

        $formData = '1,2';
        $expectedData = new ArrayCollection([$entity1, $entity2]);

        $this->transformer->expects($this->exactly(2))
                          ->method('findEntityByIdentifier')
                          ->will($this->onConsecutiveCalls($entity1, $entity2));

        $this->transformer->expects($this->at(0))
                          ->method('findEntityByIdentifier')
                          ->with(1);

        $this->transformer->expects($this->at(1))
                          ->method('findEntityByIdentifier')
                          ->with(2);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expectedData, $form->getData());
    }

    /**
     * Tests invalid entity identifier returns no instances
     */
    public function testSubmittedInvalidEntityIdentifiers()
    {
        $form = $this->factory->create($this->formType);

        $this->transformer->expects($this->once())
                          ->method('findEntityByIdentifier')
                          ->with(1)
                          ->will($this->returnValue(null));

        $formData = '1';

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals(new ArrayCollection(), $form->getData());
    }

    /**
     * Tests the form view displays a single entity instance correctly
     */
    public function testDisplaySingleEntityInstance()
    {
        $form = $this->factory->create(
            $this->formType,
            null,
            ['max_selections' => 1]
        );

        $entity = new MockEntity(1);
        $form->setData($entity);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($entity, $form->getData());

        $formView = $form->createView();

        $this->assertEquals(1, $form->getConfig()->getAttribute('data-max-selections'));
    }

    /**
     * Tests the form view displays a collection of entities correctly
     */
    public function testDisplayEntityCollection()
    {
        $form = $this->factory->create($this->formType);

        $entity1 = new MockEntity(1);
        $entity2 = new MockEntity(2);
        $entity3 = new MockEntity(3);

        $formData = new ArrayCollection([$entity1, $entity2, $entity3]);
        $form->setData($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($formData, $form->getData());
    }
}

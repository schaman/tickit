<?php

/*
 * 
 * Tickit, an source web based bug management tool.
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
 * 
 */

namespace Tickit\ProjectBundle\Tests\Form\Type;

use Symfony\Component\EventDispatcher\Tests\EventDispatcherTest;
use Tickit\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;
use Tickit\ProjectBundle\Entity\EntityAttribute;
use Tickit\ProjectBundle\Form\Event\EntityAttributeFormBuildEvent;
use Tickit\ProjectBundle\Form\Type\EntityAttributeFormType;

/**
 * EntityAttributeFormType tests.
 *
 * @package Tickit\ProjectBundle\Tests\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class EntityAttributeFormTypeTest extends AbstractFormTypeTestCase
{
    /**
     * Setup
     */
    protected function setUp()
    {
        $event = new EntityAttributeFormBuildEvent();
        $event->addEntityChoice('\Tickit\ProjectBundle\Entity\Project', 'Project');

        $dispatcher = $this->getMock('\Symfony\Component\HttpKernel\Tests\Fixtures\TestEventDispatcher');

        $dispatcher->expects($this->any())
                   ->method('dispatch')
                   ->will($this->returnValue($event));

        $this->formType = new EntityAttributeFormType($dispatcher);

        parent::setUp();
    }

    /**
     * Tests the form submit
     */
    public function testSubmitValidData()
    {
        $form = $this->factory->create($this->formType);

        $entity = new EntityAttribute();
        $entity->setEntity('\Tickit\ProjectBundle\Entity\Project')
               ->setName('name')
               ->setDefaultValue('default')
               ->setAllowBlank(true);

        $form->setData($entity);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($entity, $form->getData());

        $expectedViewComponents = array('type', 'entity', 'name', 'allow_blank', 'default_value');
        $this->assertViewHasComponents($expectedViewComponents, $form->createView());
    }
}

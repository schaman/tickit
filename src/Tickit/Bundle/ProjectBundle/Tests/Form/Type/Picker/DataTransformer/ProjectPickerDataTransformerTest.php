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

namespace Tickit\Bundle\ProjectBundle\Tests\Form\Type\Picker\DataTransformer;

use Tickit\Bundle\ProjectBundle\Form\Type\Picker\ProjectPickerDataTransformer;
use Tickit\Component\Model\Project\Project;
use Tickit\Component\Test\AbstractUnitTest;

/**
 * ProjectPickerDataTransformer tests
 *
 * @package Tickit\Bundle\ProjectBundle\Tests\Form\Type\Picker\DataTransformer
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectPickerDataTransformerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $manager;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->manager = $this->getMockBuilder('Tickit\Component\Entity\Manager\ProjectManager')
                              ->disableOriginalConstructor()
                              ->getMock();
    }

    /**
     * Tests the transformEntityToSimpleObject() method
     *
     * @dataProvider getTransformFixtures
     */
    public function testTransformEntityToSimpleObject($entity, $expectedObject)
    {
        $transformer = $this->getTransformer();
        $method = static::getNonAccessibleMethod(get_class($transformer), 'transformEntityToSimpleObject');

        $object = $method->invokeArgs($transformer, [$entity]);

        $this->assertEquals($expectedObject, $object);
    }

    /**
     * @return array
     */
    public function getTransformFixtures()
    {
        $project = new Project();
        $project->setId(66)
                ->setName('New Website Build');
        $object = new \stdClass();
        $object->id = 66;
        $object->text = 'New Website Build';

        return [
            [$project, $object]
        ];
    }

    /**
     * Tests the findEntityByIdentifier() method
     *
     * @dataProvider getFindByFixtures
     */
    public function testFindEntityByIdentifier($identifier, $returnedEntity)
    {
        $transformer = $this->getTransformer();
        $method = static::getNonAccessibleMethod(get_class($transformer), 'findEntityByIdentifier');

        $this->manager->expects($this->once())
                      ->method('find')
                      ->with($identifier)
                      ->will($this->returnValue($returnedEntity));

        $this->assertEquals($returnedEntity, $method->invokeArgs($transformer, [$identifier]));
    }

    /**
     * @return array
     */
    public function getFindByFixtures()
    {
        $project = new Project();
        $project->setId(52)
                ->setName('iOS App');

        return [
            [52, $project]
        ];
    }

    /**
     * Gets a new transformer instance
     *
     * @return ProjectPickerDataTransformer
     */
    private function getTransformer()
    {
        return new ProjectPickerDataTransformer($this->manager);
    }
}

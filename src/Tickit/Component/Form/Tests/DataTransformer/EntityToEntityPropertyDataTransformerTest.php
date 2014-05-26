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

namespace Tickit\Component\Form\Tests\DataTransformer;

use Tickit\Component\Form\DataTransformer\EntityToEntityPropertyDataTransformer;
use Tickit\Component\Form\Tests\Fixtures\DummyEntity;
use Tickit\Component\Test\AbstractUnitTest;

/**
 * EntityToEntityPropertyDataTransformerTest
 *
 * @package Tickit\Component\Form\Tests\DataTransformer
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class EntityToEntityPropertyDataTransformerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $em;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->em = $this->getMockEntityManager();
    }

    /**
     * @dataProvider getTransformFixtures
     */
    public function testTransform($data, $entityClass, $transformProperty, $expectedReturnValue, $expectedException = null)
    {
        if (null !== $expectedException) {
            $this->setExpectedException($expectedException);
        }

        $transformer = $this->getTransformer($entityClass, $transformProperty);

        $this->assertEquals($expectedReturnValue, $transformer->transform($data));
    }

    /**
     * @return array
     */
    public function getTransformFixtures()
    {
        return [
            [null, 'Tickit\Component\Form\Tests\Fixtures\DummyEntity', 'propertyA', ''],
            [new \stdClass(), 'Tickit\Component\Form\Tests\Fixtures\DummyEntity', 'property', '', '\Symfony\Component\Form\Exception\TransformationFailedException'],
            [new DummyEntity('a', 'b'), 'Tickit\Component\Form\Tests\Fixtures\DummyEntity', 'propertyA', 'a'],
            [new DummyEntity('a', 'b'), 'Tickit\Component\Form\Tests\Fixtures\DummyEntity', 'propertyB', 'b']
        ];
    }

    /**
     * @dataProvider getReverseTransformFixtures
     */
    public function testReverseTransform($data, $entityClass, $transformProperty, $expectedReturnValue, $expectedException = null)
    {
        if (null !== $expectedException) {
            $this->setExpectedException($expectedException);
        }

        if (null !== $data) {
            $repository = $this->getMockObjectRepository();
            $repository->expects($this->once())
                       ->method('findOneBy')
                       ->with([$transformProperty => $data])
                       ->will($this->returnValue($expectedReturnValue));

            $this->em->expects($this->once())
                     ->method('getRepository')
                     ->with($entityClass)
                     ->will($this->returnValue($repository));
        }

        $transformer = $this->getTransformer($entityClass, $transformProperty);
        $transformedValue = $transformer->reverseTransform($data);

        if (null !== $expectedReturnValue) {
            $this->assertEquals($expectedReturnValue, $transformedValue);
        }
    }

    /**
     * @return array
     */
    public function getReverseTransformFixtures()
    {
        return [
            [null, 'Tickit\Component\Form\Tests\Fixtures\DummyEntity', 'propertyA', '', null],
            ['cefefa', 'Tickit\Component\Form\Tests\Fixtures\DummyEntity', 'property', null, '\Symfony\Component\Form\Exception\TransformationFailedException'],
            ['a', 'Tickit\Component\Form\Tests\Fixtures\DummyEntity', 'propertyA', new DummyEntity('a', 'b')],
            ['b', 'Tickit\Component\Form\Tests\Fixtures\DummyEntity', 'propertyB', new DummyEntity('a', 'b')]
        ];
    }

    /**
     * Gets a new transformer instance
     *
     * @param string $alias             The entity class alias
     * @param string $transformProperty The property used to transform the entity to a string representation
     *
     * @return EntityToEntityPropertyDataTransformer
     */
    private function getTransformer($alias, $transformProperty)
    {
        return new EntityToEntityPropertyDataTransformer($this->em, $alias, $transformProperty);
    }
}

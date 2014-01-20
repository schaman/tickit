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

namespace Tickit\Bundle\CoreBundle\Tests\Form\Type\Picker\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Tickit\Bundle\CoreBundle\Form\Type\Picker\DataTransformer\AbstractPickerDataTransformer;
use Tickit\Bundle\CoreBundle\Tests\Form\Type\Picker\Mock\MockEntity;

/**
 * AbstractPickerDataTransformer tests
 *
 * @package Tickit\Bundle\CoreBundle\Tests\Form\Type\Picker\DataTransformer
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AbstractPickerDataTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractPickerDataTransformer
     */
    private $sut;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->sut = $this->getMockForAbstractClass(
            '\Tickit\Bundle\CoreBundle\Form\Type\Picker\DataTransformer\AbstractPickerDataTransformer'
        );
    }

    /**
     * Tests the setMaxRestrictions() method
     *
     * @dataProvider getZeroBasedValues
     */
    public function testSetMaxRestrictionsIgnoresZeroBasedValue($zeroValue)
    {
        $this->sut->setMaxSelections($zeroValue);

        $this->assertEquals(null, $this->sut->getMaxSelections());
    }
    
    /**
     * Tests the transform() method
     */
    public function testTransformReturnsEmptyStringForNullValue()
    {
        $this->assertEquals('', $this->sut->transform(null));
    }

    /**
     * Tests the transform() method
     */
    public function testTransformHandlesSingleEntityCorrectly()
    {
        $entity = new MockEntity(1, 'Forename Surname');
        $simpleObject = new \stdClass;
        $simpleObject->id = 1;
        $simpleObject->text = 'Forename Surname';

        $this->sut->expects($this->once())
                  ->method('transformEntityToSimpleObject')
                  ->with($entity)
                  ->will($this->returnValue($simpleObject));

        $expectedData = '[{"id":1,"text":"Forename Surname"}]';
        $this->assertEquals($expectedData, $this->sut->transform($entity));
    }

    /**
     * Tests the transform() method
     *
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     */
    public function testTransformThrowsExceptionForCollectionWithSingleRestrictionEnabled()
    {
        $this->sut->setMaxSelections(1);

        $collection = new ArrayCollection(
            [new MockEntity(1), new MockEntity(2), new MockEntity(4)]
        );

        $this->sut->transform($collection);
    }

    /**
     * Tests the transform() method
     */
    public function testTransformHandlesEntityCollectionCorrectly()
    {
        $simpleObjects = [];
        $entities = [];

        for ($i = 1; $i < 4; $i++) {
            $entities[$i] = new MockEntity($i, sprintf('Forename%d Surname%d', $i, $i));

            $simpleObject = new \stdClass;
            $simpleObject->id = $entities[$i]->getId();
            $simpleObject->text = $entities[$i]->getName();
            $simpleObjects[$i] = $simpleObject;
        }

        $this->sut->expects($this->exactly(3))
                  ->method('transformEntityToSimpleObject')
                  ->will($this->onConsecutiveCalls($simpleObjects[1], $simpleObjects[2], $simpleObjects[3]));

        $this->sut->expects($this->at(0))
                  ->method('transformEntityToSimpleObject')
                  ->with($entities[1]);

        $this->sut->expects($this->at(1))
                  ->method('transformEntityToSimpleObject')
                  ->with($entities[2]);

        $this->sut->expects($this->at(2))
                  ->method('transformEntityToSimpleObject')
                  ->with($entities[3]);

        $collection = new ArrayCollection($entities);

        $expectedData = '[{"id":1,"text":"Forename1 Surname1"},{"id":2,"text":"Forename2 Surname2"},{"id":3,"text":"Forename3 Surname3"}]';
        $this->assertEquals($expectedData, $this->sut->transform($collection));
    }

    /**
     * Tests the reverseTransform() method
     */
    public function testReverseTransformReturnsNullForEmptyString()
    {
        $this->assertNull($this->sut->reverseTransform(''));
    }

    /**
     * Tests the reverseTransform() method
     */
    public function testReverseTransformSkipsInvalidIdentifiers()
    {
        $this->sut->expects($this->exactly(2))
                  ->method('findEntityByIdentifier')
                  ->will($this->onConsecutiveCalls(new MockEntity(1), null));

        $this->sut->expects($this->at(0))
                  ->method('findEntityByIdentifier')
                  ->with(1);

        $this->sut->expects($this->at(1))
                  ->method('findEntityByIdentifier')
                  ->with(99);

        $expected = new ArrayCollection([new MockEntity(1)]);
        $this->assertEquals($expected, $this->sut->reverseTransform('1, 99'));
    }

    /**
     * Tests the reverseTransform() method
     */
    public function testReverseTransformHandlesSingleIdentifierCorrectly()
    {
        $this->sut->expects($this->once())
                  ->method('findEntityByIdentifier')
                  ->with(1)
                  ->will($this->returnValue(new MockEntity(1)));

        $expected = new ArrayCollection([new MockEntity(1)]);
        $this->assertEquals($expected, $this->sut->reverseTransform('1'));
    }

    /**
     * Tests the reverseTransform() method
     */
    public function testReverseTransformHandlesSingleIdentifierWithSingleRestrictionEnabledCorrectly()
    {
        $this->sut->setMaxSelections(1);
        $this->sut->expects($this->once())
                  ->method('findEntityByIdentifier')
                  ->with(1)
                  ->will($this->returnValue(new MockEntity(1)));

        $expected = new MockEntity(1);
        $this->assertEquals($expected, $this->sut->reverseTransform('1'));
    }

    /**
     * Tests the reverseTransform() method
     */
    public function testReverseTransformHandlesMultipleIdentifiersCorrectly()
    {
        $this->sut->expects($this->exactly(2))
                  ->method('findEntityByIdentifier')
                  ->will($this->onConsecutiveCalls(new MockEntity(1), new MockEntity(2)));

        $this->sut->expects($this->at(0))
                  ->method('findEntityByIdentifier')
                  ->with(1);

        $this->sut->expects($this->at(1))
                  ->method('findEntityByIdentifier')
                  ->with(2);

        $expected = new ArrayCollection([new MockEntity(1), new MockEntity(2)]);
        $this->assertEquals($expected, $this->sut->reverseTransform('1, 2'));
    }

    /**
     * Tests the reverseTransform() method
     *
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     */
    public function testReverseTransformThrowsExceptionForMultipleIdentifiersWithSingleRestrictionEnabled()
    {
        $this->sut->setMaxSelections(1);

        $this->sut->reverseTransform('1,3,4,5');
    }

    public function getZeroBasedValues()
    {
        return [
            ['0'], [0], [''], ['false'], [false]
        ];
    }
}

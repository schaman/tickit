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

namespace Tickit\Component\Decorator\Tests;

use Tickit\Component\Decorator\DomainObjectArrayDecorator;
use Tickit\Component\Decorator\Tests\Mock\MockDomainObject;

/**
 * DomainObjectArrayDecorator tests
 *
 * @package Tickit\Component\Decorator\Tests
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class DomainObjectArrayDecoratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the decorate() method
     *
     * @expectedException \InvalidArgumentException
     */
    public function testDecorateThrowsExceptionForNonObject()
    {
        $decorator = new DomainObjectArrayDecorator();
        $decorator->decorate('', array());
    }

    /**
     * Tests the decorate() method
     *
     * @expectedException \RuntimeException
     */
    public function testDecorateThrowsExceptionForInaccessibleProperty()
    {
        $decorator = new DomainObjectArrayDecorator();
        $decorator->decorate(new MockDomainObject(), array('fake'));
    }

    /**
     * Tests the decorate() method
     *
     * @expectedException \RuntimeException
     */
    public function testDecorateThrowsExceptionForInaccessibleChildProperty()
    {
        $decorator = new DomainObjectArrayDecorator();
        $decorator->decorate(new MockDomainObject(), array('childProperty.fake'));
    }

    /**
     * Tests the decorate() method
     */
    public function testDecorateHandlesMockObjectCorrectly()
    {
        $valueObjectValue = 'value object value';
        $decorator = new DomainObjectArrayDecorator();
        $mock      = new MockDomainObject($valueObjectValue);

        $decorated = $decorator->decorate(
            $mock,
            array(
                'name',
                'active',
                'enabled',
                'date',
                'childObject.enabled',
                'childObject.childObject.enabled',
                'valueObject'
            ),
            array(
                'static' => 'value'
            )
        );

        $this->assertInternalType('array', $decorated);
        $this->assertEquals('name', $decorated['name']);
        $this->assertTrue($decorated['active']);
        $this->assertFalse($decorated['enabled']);
        $this->assertEquals(date('Y-m-d H:i:s'), $decorated['date']);
        $this->assertTrue($decorated['childObject.enabled']);
        $this->assertTrue($decorated['childObject.childObject.enabled']);
        $this->assertEquals('value', $decorated['static']);
        $this->assertEquals($valueObjectValue, $decorated['valueObject']);
    }

    /**
     * Tests the decorate() method
     *
     * @dataProvider getDecorateWithCustomMappingsFixtures
     */
    public function testDecorateHandlesCustomPropertyMappings($fieldsToDecorate, $customMappings)
    {
        $decorator = new DomainObjectArrayDecorator();
        $decorator->setPropertyMappings($customMappings);
        $mock = new MockDomainObject();

        $decorated = $decorator->decorate($mock, $fieldsToDecorate, ['static' => 'value']);
        $nonCustomFields = array_diff($fieldsToDecorate, array_keys($customMappings));
        foreach ($nonCustomFields as $field) {
            $this->assertArrayHasKey($field, $decorated);
        }

        foreach ($customMappings as $customFieldName) {
            $this->assertArrayHasKey($customFieldName, $decorated);
        }
    }

    public function getDecorateWithCustomMappingsFixtures()
    {
        return [
            [
                ['name', 'active', 'enabled', 'childObject.enabled'],
                ['name' => 'custom-name', 'childObject.enabled' => 'sub-enabled']
            ],
            [['name', 'active', 'childObject.enabled'], []]
        ];
    }

    /**
     * @dataProvider getDecorateForJsonSerializeFixtures
     */
    public function testDecorateHandlesJsonSerializableChildObjects(
        MockDomainObject $object,
        array $propertyNames,
        array $expected
    ) {
        $decorator = new DomainObjectArrayDecorator();

        $this->assertEquals($expected, $decorator->decorate($object, $propertyNames));
    }

    /**
     * @return array
     */
    public function getDecorateForJsonSerializeFixtures()
    {
        $expected = [
            'name' => 'name',
            'active' => true,
            'childObject' => [
                'enabled' => true,
                'childObject' => [
                    'enabled' => true
                ]
            ]
        ];

        return [
            [new MockDomainObject(), ['name', 'active', 'childObject'], $expected],
        ];
    }
}

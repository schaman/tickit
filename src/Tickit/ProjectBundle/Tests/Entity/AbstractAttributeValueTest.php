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

namespace Tickit\ProjectBundle\Tests\Entity;

use Tickit\ProjectBundle\Entity\AbstractAttribute;
use Tickit\ProjectBundle\Entity\AbstractAttributeValue;

/**
 * AbstractAttributeValueTest tests
 *
 * @package Tickit\ProjectBundle\Tests\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AbstractAttributeValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the factory() method
     *
     * @expectedException \InvalidArgumentException
     */
    public function testFactoryThrowsExceptionForInvalidAttributeType()
    {
        AbstractAttributeValue::factory('invalid type');
    }

    /**
     * Tests the factory() method
     *
     * @param string $type             The attribute type to test
     * @param string $expectedInstance The expected instance type
     *
     * @dataProvider getTypes
     */
    public function testFactoryProducesExpectedInstance($type, $expectedInstance)
    {
        $instance = AbstractAttributeValue::factory($type);

        $this->assertInstanceOf($expectedInstance, $instance);
    }

    /**
     * Gets attribute types for test
     *
     * @return array
     */
    public function getTypes()
    {
        return array(
            array(AbstractAttribute::TYPE_CHOICE, 'Tickit\ProjectBundle\Entity\ChoiceAttributeValue'),
            array(AbstractAttribute::TYPE_ENTITY, 'Tickit\ProjectBundle\Entity\EntityAttributeValue'),
            array(AbstractAttribute::TYPE_LITERAL, 'Tickit\ProjectBundle\Entity\LiteralAttributeValue')
        );
    }
}

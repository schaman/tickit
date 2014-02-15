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

namespace Tickit\Component\Model\Tests\Project;

use Tickit\Component\Model\Project\AbstractAttribute;

/**
 * AbstractAttribute tests
 *
 * @package Tickit\Component\Model\Tests\Project
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AbstractAttributeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the factory() method
     */
    public function testFactoryReturnsCorrectInstanceForChoiceType()
    {
        $attribute = AbstractAttribute::factory(AbstractAttribute::TYPE_CHOICE);

        $this->assertInstanceOf('\Tickit\Component\Model\Project\ChoiceAttribute', $attribute);
    }

    /**
     * Tests the factory() method
     */
    public function testFactoryReturnsCorrectInstanceForEntityType()
    {
        $attribute = AbstractAttribute::factory(AbstractAttribute::TYPE_ENTITY);

        $this->assertInstanceOf('\Tickit\Component\Model\Project\EntityAttribute', $attribute);
    }

    /**
     * Tests the factory() method
     */
    public function testFactoryReturnsCorrectInstanceForLiteralType()
    {
        $attribute = AbstractAttribute::factory(AbstractAttribute::TYPE_LITERAL);

        $this->assertInstanceOf('\Tickit\Component\Model\Project\LiteralAttribute', $attribute);
    }

    /**
     * Tests the factory() method
     *
     * @expectedException \InvalidArgumentException
     */
    public function testFactoryThrowsExceptionForInvalidType()
    {
        AbstractAttribute::factory('invalid type');
    }
}

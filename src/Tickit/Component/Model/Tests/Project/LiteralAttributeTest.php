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
use Tickit\Component\Model\Project\LiteralAttribute;

/**
 * LiteralAttribute tests
 *
 * @package Tickit\Component\Model\Tests\Project
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class LiteralAttributeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The attribute under test
     *
     * @var LiteralAttribute
     */
    private $attribute;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->attribute = new LiteralAttribute();
    }
    
    /**
     * Tests the setValidationType() method
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetValidationTypeThrowsExceptionForInvalidType()
    {
        $this->attribute->setValidationType('invalid type');
    }

    /**
     * Tests the setValidationType() method
     */
    public function testSetValidationTypeAcceptsValidType()
    {
        $this->attribute->setValidationType(LiteralAttribute::VALIDATION_DATE);

        $this->assertEquals(LiteralAttribute::VALIDATION_DATE, $this->attribute->getValidationType());
    }

    /**
     * Tests the getType() method
     */
    public function testGetTypeReturnsCorrectType()
    {
        $this->assertEquals(AbstractAttribute::TYPE_LITERAL, $this->attribute->getType());
    }
}

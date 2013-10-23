<?php

/*
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
 */

namespace Tickit\ProjectBundle\Tests\Entity;

use Tickit\ProjectBundle\Entity\LiteralAttribute;
use Tickit\ProjectBundle\Entity\LiteralAttributeValue;

/**
 * LiteralAttributeValue tests
 *
 * @package Tickit\ProjectBundle\Tests\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class LiteralAttributeValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The attribute under test
     *
     * @var LiteralAttributeValue
     */
    private $attributeValue;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->attributeValue = new LiteralAttributeValue();
    }
    
    /**
     * Tests setValue() method
     */
    public function testSetValueFormatsDateCorrectly()
    {
        $now = $this->getMock('\DateTime');
        $now->expects($this->once())
            ->method('format')
            ->with('Y-m-d');

        $attribute = new LiteralAttribute();
        $attribute->setValidationType(LiteralAttribute::VALIDATION_DATE);
        $this->attributeValue
             ->setAttribute($attribute)
             ->setValue($now);
    }

    /**
     * Tests setValue() method
     */
    public function testSetValueFormatsDateTimeCorrectly()
    {
        $now = $this->getMock('\DateTime');
        $now->expects($this->once())
            ->method('format')
            ->with('Y-m-d H:i:s');

        $attribute = new LiteralAttribute();
        $attribute->setValidationType(LiteralAttribute::VALIDATION_DATETIME);

        $this->attributeValue
             ->setAttribute($attribute)
             ->setValue($now);
    }

    /**
     * Tests getValue() method
     */
    public function testGetValueReturnsSameInstanceForDateTime()
    {
        $now = new \DateTime();

        $attribute = new LiteralAttribute();
        $attribute->setValidationType(LiteralAttribute::VALIDATION_DATETIME);

        $this->attributeValue
             ->setAttribute($attribute)
             ->setValue($now);

        $this->assertEquals($now, $this->attributeValue->getValue());
    }
}

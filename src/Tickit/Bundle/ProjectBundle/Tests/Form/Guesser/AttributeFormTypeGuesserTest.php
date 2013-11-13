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

namespace Tickit\Bundle\ProjectBundle\Tests\Form\Guesser;

use Tickit\Bundle\CoreBundle\Tests\AbstractUnitTest;
use Tickit\Bundle\ProjectBundle\Entity\AbstractAttribute;
use Tickit\Bundle\ProjectBundle\Form\Guesser\AttributeFormTypeGuesser;

/**
 * AttributeFormTypeGuesser tests
 *
 * @package Tickit\Bundle\ProjectBundle\Tests\Form\Guesser
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AttributeFormTypeGuesserTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $entityAttributeFormType;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->entityAttributeFormType = $this->getMockBuilder('\Tickit\Bundle\ProjectBundle\Form\Type\EntityAttributeFormType')
                                              ->disableOriginalConstructor()
                                              ->getMock();
    }

    /**
     * Tests the guessByAttributeType() method
     *
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function testGuessByAttributeTypeThrowsExceptionForInvalidAttributeType()
    {
        $this->getGuesser()->guessByAttributeType('something not valid');
    }

    /**
     * Tests the guessByAttributeType() method
     *
     * @return void
     */
    public function testGuessByAttributeTypeReturnsCorrectTypeForLiteralAttribute()
    {
        $formType = $this->getGuesser()->guessByAttributeType(AbstractAttribute::TYPE_LITERAL);

        $this->assertInstanceOf('Tickit\Bundle\ProjectBundle\Form\Type\LiteralAttributeFormType', $formType);
    }

    /**
     * Tests the guessByAttributeType() method
     *
     * @return void
     */
    public function testGuessByAttributeTypeReturnsCorrectTypeForEntityAttribute()
    {
        $formType = $this->getGuesser()->guessByAttributeType(AbstractAttribute::TYPE_ENTITY);

        $this->assertSame($this->entityAttributeFormType, $formType);
    }

    /**
     * Tests the guessByAttributeType() method
     *
     * @return void
     */
    public function testGuessByAttributeTypeReturnsCorrectTypeForChoiceAttribute()
    {
        $guesser = $this->getGuesser();
        $formType = $guesser->guessByAttributeType(AbstractAttribute::TYPE_CHOICE);

        $this->assertInstanceOf('Tickit\Bundle\ProjectBundle\Form\Type\ChoiceAttributeFormType', $formType);
    }

    /**
     * Gets a guesser instance
     *
     * @return AttributeFormTypeGuesser
     */
    private function getGuesser()
    {
        return new AttributeFormTypeGuesser($this->entityAttributeFormType);
    }
}

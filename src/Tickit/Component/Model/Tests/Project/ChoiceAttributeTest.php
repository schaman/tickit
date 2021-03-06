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

use Doctrine\Common\Collections\ArrayCollection;
use Tickit\Component\Model\Project\ChoiceAttribute;
use Tickit\Component\Model\Project\ChoiceAttributeChoice;

/**
 * ChoiceAttribute tests
 *
 * @package Tickit\Component\Model\Tests\Project
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ChoiceAttributeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The attribute under test
     *
     * @var ChoiceAttribute
     */
    private $attribute;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->attribute = new ChoiceAttribute();
    }
    
    /**
     * Tests the getChoicesAsArray() method
     */
    public function testGetChoicesAsArrayReturnsCorrectArray()
    {
        $choice1 = new ChoiceAttributeChoice();
        $choice1->setName('name1')
                ->setId(1);

        $choice2 = clone $choice1;
        $choice2->setName('name2')
                ->setId(2);

        $choices = new ArrayCollection(array($choice1, $choice2));

        $this->attribute->setChoices($choices);

        $expected = array(
            1 => 'name1',
            2 => 'name2'
        );

        $this->assertEquals($expected, $this->attribute->getChoicesAsArray());
    }
}

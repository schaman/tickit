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

namespace Tickit\Component\Issue\Tests\DataTransformer;

use Tickit\Component\Issue\DataTransformer\StringToIssueNumberDataTransformer;

/**
 * Tests StringToIssueNumberDataTransformer
 *
 * @package Tickit\Component\Issue\Tests\DataTransformer
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class StringToIssueNumberDataTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getTransformData
     */
    public function testTransform($issueNumber, $prefix, $number, $expectedException = null)
    {
        $transformer = new StringToIssueNumberDataTransformer();

        if (null !== $expectedException) {
            $this->setExpectedException($expectedException);
        }

        $valueObject = $transformer->transform($issueNumber);

        $this->assertEquals($prefix, $valueObject->getPrefix());
        $this->assertEquals($number, $valueObject->getNumber());
    }

    public function getTransformData()
    {
        return [
            [ 'TEST12345', 'TEST', 12345 ],
            [ '4tuidfhs', null, null, '\DomainException' ]
        ];
    }
}

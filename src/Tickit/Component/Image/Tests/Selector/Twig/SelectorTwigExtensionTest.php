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

namespace Tickit\Component\Image\Tests\Selector\Twig;

use Tickit\Component\Image\Selector\Twig\SelectorTwigExtension;

/**
 * SelectorTwigExtension tests
 *
 * @package Tickit\Component\Image\Tests\Selector\Twig
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class SelectorTwigExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $selector;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->selector = $this->getMock('\Tickit\Component\Image\Selector\ImageSelectorInterface');
    }

    /**
     * Tests the getFunctions() method
     *
     * @dataProvider getFunctionFixtures()
     */
    public function testGetFunctions($twigMethodName, $expectedException = null)
    {
        if ($expectedException !== null) {
            $this->setExpectedException($expectedException);
        }

        $selector = new SelectorTwigExtension($this->selector, $twigMethodName);
        $functions = $selector->getFunctions();

        if ($expectedException === null) {
            $this->assertInternalType('array', $functions);
            $this->assertCount(1, $functions);

            /** @var \Twig_SimpleFunction $function */
            $function = array_shift($functions);
            $this->assertInstanceOf('\Twig_SimpleFunction', $function);
            $this->assertEquals($twigMethodName, $function->getName());
            $this->assertEquals(array($this->selector, 'select'), $function->getCallable());
        }
    }

    /**
     * @return array
     */
    public function getFunctionFixtures()
    {
        return [
            ['select'],
            ['', '\InvalidArgumentException']
        ];
    }
}

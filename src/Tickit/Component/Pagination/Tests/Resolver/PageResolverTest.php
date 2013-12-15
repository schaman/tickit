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

namespace Tickit\Component\Pagination\Tests\Resolver;

use Tickit\Component\Pagination\PageBounds;
use Tickit\Component\Pagination\Resolver\PageResolver;

/**
 * PageResolver tests
 *
 * @package Tickit\Component\Pagination\Tests\Resolver
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PageResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the resolve() method
     *
     * @expectedException \InvalidArgumentException
     */
    public function testResolveThrowsExceptionForZeroPageNumber()
    {
        $this->getResolver()->resolve(0);
    }

    /**
     * Tests the resolve() method
     *
     * @expectedException \InvalidArgumentException
     */
    public function testResolveThrowsExceptionForNonIntegerPageNumber()
    {
        $this->getResolver()->resolve('not an integer');
    }

    /**
     * Tests the resolve() method
     */
    public function testResolveAcceptsIntegerAsAString()
    {
        $expected = new PageBounds(51, PageResolver::ITEMS_PER_PAGE);

        $this->assertEquals($expected, $this->getResolver()->resolve('2'));
    }

    /**
     * Tests the resolve() method
     */
    public function testResolveReturnsCorrectBoundsForFirstPage()
    {
        $expected = new PageBounds(0, PageResolver::ITEMS_PER_PAGE);

        $this->assertEquals($expected, $this->getResolver()->resolve(1));
    }

    /**
     * Tests the resolve() method
     */
    public function testResolveReturnsCorrectBoundsForLargePageNumber()
    {
        $expected = new PageBounds(251, PageResolver::ITEMS_PER_PAGE);

        $this->assertEquals($expected, $this->getResolver()->resolve(10));
    }

    /**
     * Gets a page resolver
     *
     * @return PageResolver
     */
    private function getResolver()
    {
        return new PageResolver();
    }
}

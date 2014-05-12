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

namespace Tickit\Component\Pagination\Tests;

use Tickit\Component\Pagination\PageData;

/**
 * PageData tests
 *
 * @package Tickit\Component\Pagination\Tests
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PageDataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getPageDataFixtures
     */
    public function testPageDataBuildsCorrectly(array $data, $total, $perPage, $pageNumber, $expectedTotalPages)
    {
        $pageData = new PageData($data, $total, $perPage, $pageNumber);

        $this->assertEquals($data, $pageData->getData());
        $this->assertEquals($total, $pageData->getTotal());
        $this->assertEquals($pageNumber, $pageData->getCurrentPage());
        $this->assertEquals($expectedTotalPages, $pageData->getPages());
    }

    /**
     * @return array
     */
    public function getPageDataFixtures()
    {
        $object1 = new \stdClass();
        $object2 = new \ArrayObject();
        $data = [$object1, $object2];

        return [
            [$data, 50, 2, 4, 25],
            [$data, 2, 2, 1, 1],
            [[], 0, 10, 1, 0]
        ];
    }
}

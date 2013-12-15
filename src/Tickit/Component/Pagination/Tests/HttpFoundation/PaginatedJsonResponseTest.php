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

namespace Tickit\Component\Pagination\Tests\HttpFoundation;

use Tickit\Component\Pagination\HttpFoundation\PaginatedJsonResponse;

/**
 * PaginatedJsonResponse tests
 *
 * @package Tickit\Component\Pagination\Tests\HttpFoundation
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PaginatedJsonResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testResponseBuildsCorrectly()
    {
        $data = array_fill(0, 50, new \stdClass());
        $response = new PaginatedJsonResponse($data, 500, 10, 3);

        $decodedResponse = json_decode($response->getContent());
        $this->assertInstanceOf('stdClass', $decodedResponse);
        $this->assertEquals(50, $decodedResponse->pages);
        $this->assertEquals(500, $decodedResponse->total);
        $this->assertEquals($data, $decodedResponse->data);
        $this->assertEquals(3, $decodedResponse->currentPage);
    }

    public function testResponseHandlesPagingCorrectly()
    {
        $data = array_fill(0, 10, new \stdClass());
        $response = new PaginatedJsonResponse($data, 35, 10, 1);

        $decodedResponse = json_decode($response->getContent());

        $this->assertInstanceOf('stdClass', $decodedResponse);
        $this->assertEquals(4, $decodedResponse->pages);
    }
}

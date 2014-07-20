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

namespace Tickit\Component\HttpFoundation\Tests\Response;

use Tickit\Component\HttpFoundation\Response\RawJsonResponse;

/**
 * RawJsonResponse tests
 *
 * @package Tickit\Component\HttpFoundation\Tests\Response
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class RawJsonResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getRawJsonData
     */
    public function testConstructor($data)
    {
        $response = new RawJsonResponse($data);

        $this->assertEquals($data, $response->getContent());
    }

    /**
     * @dataProvider getRawJsonData
     */
    public function testSetData($data)
    {
        $response = new RawJsonResponse();
        $response->setData($data);

        $this->assertEquals($data, $response->getContent());
    }

    public function getRawJsonData()
    {
        $data = array(
            'property' => 1,
            'hello' => 'something',
            'object' => new \stdClass()
        );

        return [
            [json_encode($data)]
        ];
    }
}

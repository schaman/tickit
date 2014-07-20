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

namespace Tickit\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Raw JSON response.
 *
 * Provides a response object for serving raw JSON response to
 * the client.
 *
 * @package Tickit\Component\HttpFoundation\Response
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class RawJsonResponse extends JsonResponse
{
    /**
     * Constructor
     *
     * @param string  $data    The raw JSON data
     * @param integer $status  The status code (defaults to 200)
     */
    public function __construct($data = null, $status = 200)
    {
        $this->headers = new ResponseHeaderBag([]);
        $this->setData($data);

    }

    /**
     * Sets the raw JSON data on the response object
     *
     * @param array|string $data The raw JSON data
     *
     * @return JsonResponse
     */
    public function setData($data = array())
    {
        $this->data = (string) $data;

        $this->update();
    }
}

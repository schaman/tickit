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

namespace Tickit\Component\Pagination\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Paginated JSON response.
 *
 * A JSON response that specifically adds paging information to
 * the output and formats the response to an agreed standard.
 *
 * This response class will also force the response content to be
 * an object, rather than an array. This is to prevent JSON hijacking.
 *
 * @package Tickit\Component\Pagination\Response
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @see https://www.owasp.org/index.php/OWASP_AJAX_Security_Guidelines#Always_return_JSON_with_an_Object_on_the_outside
 */
class PaginatedJsonResponse extends JsonResponse
{
    /**
     * The total number of items available to page through.
     *
     * @var integer
     */
    private $total;

    /**
     * The number of items per page
     *
     * @var integer
     */
    private $perPage;

    /**
     * The current page number
     *
     * @var integer
     */
    private $page;

    /**
     * Constructor.
     *
     * @param array   $data         The array of data
     * @param integer $totalItems   The total number of items available to page through
     * @param integer $itemsPerPage The number of items in a page
     * @param integer $currentPage  The current page number of the data provided
     */
    public function __construct(array $data, $totalItems, $itemsPerPage, $currentPage)
    {
        $this->total = $totalItems;
        $this->perPage = $itemsPerPage;
        $this->page = $currentPage;

        parent::__construct($data);
    }

    /**
     * {@inheritDoc}
     *
     * @param array $data The data to be sent in the response
     *
     * @return JsonResponse
     */
    public function setData($data = array())
    {
        $responseData = new \stdClass();
        $responseData->data = $data;
        $responseData->total = $this->total;
        $responseData->pages = ceil($this->total / $this->perPage);
        $responseData->currentPage = $this->page;

        return parent::setData($responseData);
    }
}

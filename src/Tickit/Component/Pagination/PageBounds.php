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

namespace Tickit\Component\Pagination;

/**
 * PageBounds
 *
 * Represents the bounds for a page in a query result.
 *
 * @package Tickit\Component\Pagination
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PageBounds
{
    /**
     * The offset at which this page starts from
     *
     * @var integer
     */
    private $offset;

    /**
     * The maximum number of results in this page
     *
     * @var integer
     */
    private $maxResults;

    /**
     * Constructor.
     *
     * @param integer $offset     The offset at which this page starts from
     * @param integer $maxResults The maximum number of results in this page
     */
    public function __construct($offset, $maxResults)
    {
        $this->offset = $offset;
        $this->maxResults = $maxResults;
    }

    /**
     * Gets the maximum number of results for the page
     *
     * @return integer
     */
    public function getMaxResults()
    {
        return $this->maxResults;
    }

    /**
     * Gets the offset
     *
     * @return integer
     */
    public function getOffset()
    {
        return $this->offset;
    }
}

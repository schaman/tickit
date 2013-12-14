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

namespace Tickit\Component\Pagination\Resolver;

use Tickit\Component\Pagination\PageBounds;

/**
 * Page resolver.
 *
 * Resolves a page number into a PageBounds object
 *
 * @package Tickit\Component\Pagination\Resolver
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @see     Tickit\Component\Pagination\PageBounds
 */
class PageResolver implements PageResolverInterface
{
    /**
     * The maximum number of items per page
     *
     * @const integer
     */
    const ITEMS_PER_PAGE = 25;

    /**
     * Resolves a page into a PageBounds object
     *
     * @param integer $page The page number to resolve
     *
     * @throws \InvalidArgumentException If the page number is not valid (integer greater than 0)
     *
     * @return PageBounds
     */
    public function resolve($page)
    {
        if (!is_integer($page) || 0 === intval($page)) {
            throw new \InvalidArgumentException(
                sprintf('An invalid page number (%s) has been provided to the PageResolver', $page)
            );
        }

        if ($page === 1) {
            $offset = 0;
        } else {
            $offset = (static::ITEMS_PER_PAGE * $page) + 1;
        }

        return new PageBounds($offset, static::ITEMS_PER_PAGE);
    }
}

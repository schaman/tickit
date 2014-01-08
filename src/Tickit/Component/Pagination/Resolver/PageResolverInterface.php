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
 * Page resolver interface.
 *
 * Page resolvers are responsible for resolving a page number
 * into a value object that represents the upper and lower
 * bounds for that page.
 *
 * @package Tickit\Component\Pagination\Resolver
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface PageResolverInterface
{
    /**
     * Resolves a page into a PageBounds object
     *
     * @param integer $page The page number to resolve
     *
     * @return PageBounds
     */
    public function resolve($page);
}

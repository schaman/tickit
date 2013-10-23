<?php

/*
 * 
 * Tickit, an source web based bug management tool.
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
 * 
 */

namespace Tickit\NavigationBundle\Builder;

/**
 * Navigation builder interface.
 *
 * Builders provide functionality for building navigation elements in the application.
 *
 * @package Tickit\NavigationBundle\Builder
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
interface BuilderInterface
{
    /**
     * Builds the navigation component.
     *
     * @return mixed
     */
    public function build();
}

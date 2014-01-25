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

namespace Tickit\Component\Navigation\Event;

/**
 * Navigation component events collection.
 *
 * This class contains a collection of constants representing event names for the navigation component
 *
 * @package Tickit\Component\Navigation\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
final class NavigationEvents
{
    /**
     * Constant representing the name of the "navigation build" event
     *
     * @const string
     */
    const MAIN_NAVIGATION_BUILD = 'tickit.event.navigation_build';
}

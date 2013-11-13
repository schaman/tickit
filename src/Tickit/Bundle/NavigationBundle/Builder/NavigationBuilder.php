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

namespace Tickit\Bundle\NavigationBundle\Builder;

use Tickit\Bundle\NavigationBundle\Event\NavigationBuildEvent;
use Tickit\Bundle\NavigationBundle\TickitNavigationEvents;

/**
 * Main navigation builder.
 *
 * Responsible for building the main navigation structure.
 *
 * @package Tickit\Bundle\NavigationBundle\Builder
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class NavigationBuilder extends AbstractBuilder implements BuilderInterface
{
    /**
     * Builds the navigation component.
     *
     * @param string $name Navigation name
     *
     * @return \SplPriorityQueue
     */
    public function build($name = 'main')
    {
        $event = new NavigationBuildEvent($name);
        $this->dispatcher->dispatch(TickitNavigationEvents::MAIN_NAVIGATION_BUILD, $event);

        return $event->getItems();
    }
}

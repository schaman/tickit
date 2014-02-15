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

namespace Tickit\Component\Navigation\Builder;

use Tickit\Component\Navigation\Event\NavigationBuildEvent;
use Tickit\Component\Navigation\Event\NavigationEvents;

/**
 * Main navigation builder.
 *
 * Responsible for building the main navigation structure.
 *
 * @package Tickit\Component\Navigation\Builder
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class NavigationBuilder extends AbstractBuilder implements BuilderInterface
{
    const NAME_SETTINGS = 'settings';
    const NAME_MAIN     = 'main';

    /**
     * Builds the navigation component.
     *
     * @param string $name Navigation name
     *
     * @return \SplPriorityQueue
     */
    public function build($name = self::NAME_SETTINGS)
    {
        $event = new NavigationBuildEvent($name);
        $this->dispatcher->dispatch(NavigationEvents::NAVIGATION_BUILD, $event);

        return $event->getItems();
    }
}

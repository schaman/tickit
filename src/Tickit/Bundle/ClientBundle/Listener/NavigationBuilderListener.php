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

namespace Tickit\Bundle\ClientBundle\Listener;

use Tickit\Component\Navigation\Builder\NavigationBuilder;
use Tickit\Component\Navigation\Event\NavigationBuildEvent;
use Tickit\Component\Navigation\Model\NavigationItem;

/**
 * Navigation builder listener.
 *
 * Listens for the "tickit.event.navigation_build" event and attaches the bundle's
 * relevant navigation items.
 *
 * @package Tickit\Bundle\ClientBundle\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NavigationBuilderListener
{
    /**
     * Build event for the client navigation
     *
     * @param NavigationBuildEvent $event The navigation build event
     */
    public function onBuild(NavigationBuildEvent $event)
    {
        if ($event->getNavigationName() === NavigationBuilder::NAME_MAIN) {
            $item = new NavigationItem('Clients', 'client_index', 2, ['icon' => 'list-alt']);
            $event->addItem($item);
        }
    }
}

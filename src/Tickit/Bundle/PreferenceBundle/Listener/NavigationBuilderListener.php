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

namespace Tickit\Bundle\PreferenceBundle\Listener;

use Tickit\Component\Navigation\Builder\NavigationBuilder;
use Tickit\Component\Navigation\Event\NavigationBuildEvent;
use Tickit\Component\Navigation\Model\NavigationItem;

/**
 * Preference navigation builder listener.
 *
 * @package Tickit\Bundle\PreferenceBundle\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NavigationBuilderListener
{
    /**
     * Hooks into the navigation build event
     *
     * @param NavigationBuildEvent $event The event object
     */
    public function onBuild(NavigationBuildEvent $event)
    {
        if ($event->getNavigationName() === NavigationBuilder::NAME_SETTINGS) {
            $item = new NavigationItem(
                'Preferences',
                'preference_index', // TODO: this route will need updating when user preferences management is added
                1,
                ['showText' => true]
            );
            $event->addItem($item);
        }
    }
}

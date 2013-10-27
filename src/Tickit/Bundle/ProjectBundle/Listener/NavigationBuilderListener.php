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

namespace Tickit\Bundle\ProjectBundle\Listener;

use Tickit\Bundle\NavigationBundle\Event\NavigationBuildEvent;
use Tickit\Bundle\NavigationBundle\Model\NavigationItem;

/**
 * Project navigation builder listener
 *
 * @package Tickit\Bundle\ProjectBundle\Listener
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class NavigationBuilderListener
{
    /**
     * Build event for project navigation
     *
     * @param NavigationBuildEvent $event Navigation build event
     *
     * @return void
     */
    public function onBuild(NavigationBuildEvent $event)
    {
        switch ($event->getNavigationName()) {
            case 'main':
                $event->addItem(new NavigationItem('Projects', 'project_index', 8));
                break;
        }
    }
}

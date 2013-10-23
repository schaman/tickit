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

namespace Tickit\NavigationBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Tickit\NavigationBundle\Model\NavigationItem;

/**
 * Before navigation build event.
 *
 * Event that is fired when a navigation component is being built.
 *
 * @package Tickit\NavigationBundle\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class NavigationBuildEvent extends Event
{
    /**
     * The feature item (if any)
     *
     * @var array
     */
    protected $featuredItem;

    /**
     * Navigation items.
     *
     * @var \SplPriorityQueue $item
     */
    protected $items;

    /**
     * Identifier for the navigation
     *
     * @var string $name
     */
    protected $name;

    /**
     * Constructor.
     *
     * @param string $name Identifier for the navigation
     */
    public function __construct($name)
    {
        $this->name  = $name;
        $this->items = new \SplPriorityQueue();
    }

    /**
     * Get the navigation identifier
     *
     * @return string
     */
    public function getNavigationName()
    {
        return $this->name;
    }

    /**
     * Adds an item to the navigation.
     *
     * @param NavigationItem $navigationItem Navigation item
     *
     * @return void
     */
    public function addItem(NavigationItem $navigationItem)
    {
        $this->items->insert($navigationItem, $navigationItem->getPriority());
    }

    /**
     * Gets navigation items
     *
     * @return \SplPriorityQueue
     */
    public function getItems()
    {
        return $this->items;
    }
}

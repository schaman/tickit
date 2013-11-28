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

namespace Tickit\Component\Navigation\Model;

/**
 * Navigation item
 *
 * @package Tickit\Component\Navigation\Model
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class NavigationItem
{
    /**
     * Text to display in navigation
     *
     * @var string $text
     */
    private $text;

    /**
     * Navigation URL
     *
     * @var string $url
     */
    private $routeName;

    /**
     * Priority for item
     *
     * @var int $priority
     */
    private $priority;

    /**
     * Additional parameters for route generation
     *
     * @var array $params
     */
    private $params;

    /**
     * Constructor.
     *
     * @param string $text      Text to display in navigation
     * @param string $routeName Navigation URL
     * @param int    $priority  Priority for this item
     * @param array  $params    Additional parameters for route generation
     */
    public function __construct($text, $routeName, $priority, array $params = array())
    {
        $this->text      = $text;
        $this->routeName = $routeName;
        $this->priority  = $priority;
        $this->params    = $params;
    }

    /**
     * Get text to display in navigation
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Get navigation URL
     *
     * @return string
     */
    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * Get additional parameters
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Get navigation priority
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }
}

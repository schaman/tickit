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

namespace Tickit\Component\DomainObject\Tests\Decorator\Mock;

/**
 * Mock domain child object
 *
 * @package Tickit\Component\DomainObject\Tests\Decorator\Mock
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class MockDomainChildObject
{
    /**
     * Enabled property
     *
     * @var boolean
     */
    protected $enabled = true;

    /**
     * Child object
     *
     * @var MockDomainChildObject
     */
    protected $childObject;

    /**
     * Constructor.
     *
     * @param boolean $hasChild Determines whether we should initialise a child object
     */
    public function __construct($hasChild = false)
    {
        if ($hasChild) {
            $this->childObject = new MockDomainChildObject();
        }
    }

    /**
     * Returns enabled property
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    public function getChildObject()
    {
        return $this->childObject;
    }
}

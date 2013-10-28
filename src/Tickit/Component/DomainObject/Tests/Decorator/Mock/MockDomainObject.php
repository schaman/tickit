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
 * Mock domain object
 *
 * @package Tickit\Component\DomainObject\Tests\Decorator\Mock
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class MockDomainObject
{
    /**
     * String
     *
     * @var string
     */
    protected $name = 'name';

    /**
     * Boolean
     *
     * @var boolean
     */
    protected $active = true;

    /**
     * Boolean
     *
     * @var boolean
     */
    protected $enabled = false;

    /**
     * DateTime
     *
     * @var \DateTime
     */
    protected $date;

    /**
     * Child object
     *
     * @var MockDomainChildObject
     */
    protected $childObject;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->date        = new \DateTime();
        $this->childObject = new MockDomainChildObject(true);
    }

    /**
     * Returns string
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns boolean
     *
     * @return boolean
     */
    public function active()
    {
        return $this->active;
    }

    /**
     * Returns boolean
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Returns DateTime
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Returns a child object
     *
     * @return MockDomainChildObject
     */
    public function getChildObject()
    {
        return $this->childObject;
    }
}

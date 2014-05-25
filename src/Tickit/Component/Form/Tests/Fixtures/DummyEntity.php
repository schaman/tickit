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

namespace Tickit\Component\Form\Tests\Fixtures;

/**
 * DummyEntity
 *
 * @package Tickit\Component\Form\Tests\Fixtures
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class DummyEntity
{
    private $propertyValueA;
    private $propertyValueB;

    public function __construct($propertyValueA, $propertyValueB)
    {
        $this->propertyValueA = $propertyValueA;
        $this->propertyValueB = $propertyValueB;
    }

    public function getPropertyA()
    {
        return $this->propertyValueA;
    }

    public function getPropertyB()
    {
        return $this->propertyValueB;
    }
}

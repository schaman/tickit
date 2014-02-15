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

namespace Tickit\Component\Preference\Tests\Model;

use Tickit\Component\Preference\Model\Preference;

/**
 * Tests for Preference entity
 *
 * @package Tickit\Component\Preference\Tests\Model
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PreferenceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the setType() method
     *
     * Ensures that no exception is thrown for valid types
     *
     * @return void
     */
    public function testSetTypeAcceptsValidType()
    {
        $preference = new Preference();

        $preference->setType(Preference::TYPE_SYSTEM);
        $this->assertEquals(Preference::TYPE_SYSTEM, $preference->getType());

        $preference->setType(Preference::TYPE_USER);
        $this->assertEquals(Preference::TYPE_USER, $preference->getType());
    }

    /**
     * Tests the setType() method
     *
     * Ensures that the correct exception is thrown for invalid types
     *
     * @return void
     */
    public function testSetTypeDoesNotAcceptValidType()
    {
        $this->setExpectedException('InvalidArgumentException');

        $preference = new Preference();
        $preference->setType('something that is not valid');
    }

    /**
     * Tests the setSystemName() method
     *
     * Ensures that whitespace is removed
     *
     * @return void
     */
    public function testSetSystemNameStripsWhitespace()
    {
        $preference = new Preference();
        $preference->setSystemName('something with whitespace');

        $this->assertFalse(strpos(' ', $preference->getSystemName()));
    }
}

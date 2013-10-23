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

namespace Tickit\NavigationBundle\Tests\Model;

use Tickit\NavigationBundle\Model\NavigationItem;

/**
 * NavigationItem tests
 *
 * @package Tickit\NavigationBundle\Tests\Model
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NavigationItemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the __construct() method
     *
     * @return void
     */
    public function testNavigationItemSetsCorrectValuesFromConstructor()
    {
        $params = array(
            'key' => 1,
            'key2' => 'value',
            'key3' => array(
                'subkey' => 'value'
            )
        );

        $item = new NavigationItem('navigation text', 'route_name', -99, $params);

        $this->assertEquals('navigation text', $item->getText());
        $this->assertEquals('route_name', $item->getRouteName());
        $this->assertEquals(-99, $item->getPriority());
        $this->assertEquals($params, $item->getParams());
    }
}

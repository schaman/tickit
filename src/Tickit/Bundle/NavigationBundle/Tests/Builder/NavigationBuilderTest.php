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

namespace Tickit\Bundle\NavigationBundle\Tests\Builder;

use Tickit\Component\Test\AbstractUnitTest;
use Tickit\Bundle\NavigationBundle\Builder\NavigationBuilder;
use Tickit\Bundle\NavigationBundle\Event\NavigationBuildEvent;
use Tickit\Bundle\NavigationBundle\TickitNavigationEvents;

/**
 * NavigationBuilderTest tests
 *
 * @package Tickit\Bundle\NavigationBundle\Tests\Builder
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NavigationBuilderTest extends AbstractUnitTest
{
    /**
     * Tests the build() method
     */
    public function testBuildDispatchesEvent()
    {
        $expectedEvent = new NavigationBuildEvent('name of navigation');

        $dispatcher = $this->getMockEventDispatcher();
        $dispatcher->expects($this->once())
                   ->method('dispatch')
                   ->with(TickitNavigationEvents::MAIN_NAVIGATION_BUILD, $expectedEvent)
                   ->will($this->returnValue(new \SplPriorityQueue()));

        $builder = new NavigationBuilder($dispatcher);
        $return = $builder->build('name of navigation');

        $this->assertInstanceOf('\SplPriorityQueue', $return);
    }
}

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

namespace Tickit\Bundle\DashboardBundle\Tests\Listener;

use Tickit\Bundle\DashboardBundle\Listener\NavigationBuilderListener;
use Tickit\Component\Navigation\Builder\NavigationBuilder;
use Tickit\Component\Navigation\Event\NavigationBuildEvent;
use Tickit\Component\Navigation\Model\NavigationItem;

/**
 * NavigationBuilderListener tests
 *
 * @package Tickit\Bundle\DashboardBundle\Tests\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NavigationBuilderListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the onBuild() method
     *
     * @return void
     */
    public function testOnBuildAddsCorrectNavigationItemsForMainNavigation()
    {
        $event = new NavigationBuildEvent(NavigationBuilder::NAME_MAIN);
        $builder = new NavigationBuilderListener();

        $builder->onBuild($event);

        $this->assertEquals(1, $event->getItems()->count());
        $first = $event->getItems()->top();
        $this->assertInstanceOf('\Tickit\Component\Navigation\Model\NavigationItem', $first);
        /** @var NavigationItem $first */
        $this->assertEquals('Dashboard', $first->getText());
        $params = $first->getParams();
        $this->assertEquals('gauge', $params['icon']);
    }

    /**
     * Tests the onBuild() method
     *
     * @return void
     */
    public function testOnBuildDoesNotAddNavigationItemsForInvalidNavigationName()
    {
        $event = new NavigationBuildEvent('fake');
        $builder = new NavigationBuilderListener();

        $builder->onBuild($event);

        $this->assertEquals(0, $event->getItems()->count());
    }
}

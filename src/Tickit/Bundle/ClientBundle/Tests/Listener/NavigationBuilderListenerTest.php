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

namespace Tickit\Bundle\ClientBundle\Tests\Listener;

use Tickit\Bundle\ClientBundle\Listener\NavigationBuilderListener;
use Tickit\Component\Navigation\Builder\NavigationBuilder;
use Tickit\Component\Test\AbstractUnitTest;
use Tickit\Component\Navigation\Event\NavigationBuildEvent;
use Tickit\Component\Navigation\Model\NavigationItem;

/**
 * NavigationBuilderListener tests
 *
 * @package Tickit\Bundle\ClientBundle\Tests\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NavigationBuilderListenerTest extends AbstractUnitTest
{
    /**
     * Tests the onBuild() method
     */
    public function testOnBuildAddsNavigationItemForMainNavigation()
    {
        $event = new NavigationBuildEvent(NavigationBuilder::NAME_MAIN);

        $this->getListener()->onBuild($event);

        $this->assertEquals(1, $event->getItems()->count());
        /** @var NavigationItem $item */
        $item = $event->getItems()->top();

        $this->assertEquals('Clients', $item->getText());
        $this->assertEquals('client_index', $item->getRouteName());
        $params = $item->getParams();
        $this->assertEquals('list-alt', $params['icon']);
    }

    /**
     * Tests the onBuild() method
     */
    public function testOnBuildDoesNotAddNavigationItemForOtherNavigation()
    {
        $event = new NavigationBuildEvent('other');

        $this->getListener()->onBuild($event);

        $this->assertEquals(0, $event->getItems()->count());
    }

    /**
     * Gets a new listener
     *
     * @return NavigationBuilderListener
     */
    private function getListener()
    {
        return new NavigationBuilderListener();
    }
}

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

namespace Tickit\Bundle\PreferenceBundle\Tests\Listener;

use Tickit\Bundle\PreferenceBundle\Listener\NavigationBuilderListener;
use Tickit\Component\Navigation\Builder\NavigationBuilder;
use Tickit\Component\Navigation\Event\NavigationBuildEvent;
use Tickit\Component\Navigation\Model\NavigationItem;

/**
 * NavigationBuilderListener tests
 *
 * @package Tickit\Bundle\PreferenceBundle\Tests\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NavigationBuilderListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the onBuild() method
     */
    public function testOnBuildIgnoresInvalidNavigationNames()
    {
        $event = new NavigationBuildEvent(NavigationBuilder::NAME_MAIN);
        $this->getListener()->onBuild($event);

        $this->assertEquals(0, $event->getItems()->count());
    }

    /**
     * Tests the onBuild() method
     */
    public function testOnBuildAddsCorrectNavigationItem()
    {
        $event = new NavigationBuildEvent(NavigationBuilder::NAME_SETTINGS);

        $this->getListener()->onBuild($event);

        $this->assertEquals(1, $event->getItems()->count());
        /** @var NavigationItem $item */
        $item = $event->getItems()->top();

        $this->assertEquals('Preferences', $item->getText());
        $this->assertEquals('preference_index', $item->getRouteName());
        $params = $item->getParams();
        $this->assertEquals(true, $params['showText']);
    }

    /**
     * Gets a new instance
     *
     * @return NavigationBuilderListener
     */
    private function getListener()
    {
        return new NavigationBuilderListener();
    }
}

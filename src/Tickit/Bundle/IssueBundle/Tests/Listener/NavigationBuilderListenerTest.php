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

namespace Tickit\Bundle\IssueBundle\Tests\Listener;

use Tickit\Bundle\IssueBundle\Listener\NavigationBuilderListener;
use Tickit\Component\Navigation\Builder\NavigationBuilder;
use Tickit\Component\Navigation\Event\NavigationBuildEvent;
use Tickit\Component\Navigation\Model\NavigationItem;

/**
 * NavgationBuilderListener tests
 *
 * @package Tickit\Bundle\IssueBundle\Tests\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NavigationBuilderListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the onBuild() method
     */
    public function testOnBuildIgnoresInvalidNavigationNames()
    {
        $event = new NavigationBuildEvent('irrelevant name');
        $this->getListener()->onBuild($event);

        $this->assertEquals(0, $event->getItems()->count());
    }

    /**
     * Tests the onBuild() method
     */
    public function testOnBuildBuildsCorrectNavigationItems()
    {
        $event = new NavigationBuildEvent(NavigationBuilder::NAME_MAIN);
        $this->getListener()->onBuild($event);

        $items = $event->getItems();
        $this->assertEquals(2, $items->count());

        /** @var NavigationItem $first */
        $first = $items->current();
        $this->assertEquals('Create Issue', $first->getText());
        $this->assertEquals('issue_index', $first->getRouteName());
        $params = $first->getParams();
        $this->assertEquals('plus', $params['icon']);
        $this->assertEquals('add-ticket', $params['class']);
        $this->assertEquals(true, $params['showText']);

        $items->next();
        /** @var NavigationItem $second */
        $second = $items->current();
        $this->assertEquals('Issues', $second->getText());
        $this->assertEquals('issue_index', $first->getRouteName());
        $params = $second->getParams();
        $this->assertEquals('tags', $params['icon']);
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

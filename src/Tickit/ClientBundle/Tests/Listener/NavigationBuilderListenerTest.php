<?php

namespace Tickit\ClientBundle\Tests\Listener;

use Tickit\ClientBundle\Listener\NavigationBuilderListener;
use Tickit\CoreBundle\Tests\AbstractUnitTest;
use Tickit\NavigationBundle\Event\NavigationBuildEvent;
use Tickit\NavigationBundle\Model\NavigationItem;

/**
 * NavigationBuilderListener tests
 *
 * @package Tickit\ClientBundle\Tests\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NavigationBuilderListenerTest extends AbstractUnitTest
{
    /**
     * Tests the onBuild() method
     */
    public function testOnBuildAddsNavigationItemForMainNavigation()
    {
        $event = new NavigationBuildEvent('main');

        $this->getListener()->onBuild($event);

        $this->assertEquals(1, $event->getItems()->count());
        /** @var NavigationItem $item */
        $item = $event->getItems()->top();

        $this->assertEquals('Clients', $item->getText());
        $this->assertEquals('client_index', $item->getRouteName());
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

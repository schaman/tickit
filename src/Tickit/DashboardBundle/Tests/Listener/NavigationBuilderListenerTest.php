<?php

namespace Tickit\DashboardBundle\Tests\Listener;

use Tickit\DashboardBundle\Listener\NavigationBuilderListener;
use Tickit\NavigationBundle\Event\NavigationBuildEvent;
use Tickit\NavigationBundle\Model\NavigationItem;

/**
 * NavigationBuilderListener tests
 *
 * @package Tickit\DashboardBundle\Tests\Listener
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
        $event = new NavigationBuildEvent('main');
        $builder = new NavigationBuilderListener();

        $builder->onBuild($event);

        $this->assertEquals(1, $event->getItems()->count());
        $first = $event->getItems()->top();
        $this->assertInstanceOf('\Tickit\NavigationBundle\Model\NavigationItem', $first);
        /** @var NavigationItem $first */
        $this->assertEquals('Dashboard', $first->getText());
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

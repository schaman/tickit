<?php

namespace Tickit\TeamBundle\Tests\Listener;

use Tickit\TeamBundle\Listener\NavigationBuilder;
use Tickit\NavigationBundle\Event\NavigationBuildEvent;
use Tickit\NavigationBundle\Model\NavigationItem;

/**
 * NavigationBuilder tests
 *
 * @package Tickit\TeamBundle\Tests\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NavigationBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the onBuild() method
     *
     * @return void
     */
    public function testOnBuildAddsCorrectNavigationItemsForMainNavigation()
    {
        $event = new NavigationBuildEvent('main');
        $builder = new NavigationBuilder();

        $builder->onBuild($event);

        $this->assertEquals(1, $event->getItems()->count());
        $first = $event->getItems()->top();
        $this->assertInstanceOf('\Tickit\NavigationBundle\Model\NavigationItem', $first);
        /** @var NavigationItem $first */
        $this->assertEquals('Teams', $first->getText());
    }

    /**
     * Tests the onBuild() method
     *
     * @return void
     */
    public function testOnBuildDoesNotAddNavigationItemsForInvalidNavigationName()
    {
        $event = new NavigationBuildEvent('fake');
        $builder = new NavigationBuilder();

        $builder->onBuild($event);

        $this->assertEquals(0, $event->getItems()->count());
    }
}
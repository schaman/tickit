<?php

namespace Tickit\ProjectBundle\Tests\Listener;

use Tickit\NavigationBundle\Event\NavigationBuildEvent;
use Tickit\ProjectBundle\Listener\NavigationBuilder;

/**
 * NavigationBuilder tests
 *
 * @package Tickit\ProjectBundle\Tests\Listener
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

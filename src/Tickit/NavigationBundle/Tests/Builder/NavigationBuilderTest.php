<?php

namespace Tickit\NavigationBundle\Tests\Builder;

use Tickit\CoreBundle\Tests\AbstractUnitTest;
use Tickit\NavigationBundle\Builder\NavigationBuilder;
use Tickit\NavigationBundle\Event\NavigationBuildEvent;
use Tickit\NavigationBundle\TickitNavigationEvents;

/**
 * NavigationBuilderTest tests
 *
 * @package Tickit\NavigationBundle\Tests\Builder
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

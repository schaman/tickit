<?php

namespace Tickit\Bundle\TicketBundle\Tests\Listener;

use Tickit\Bundle\TicketBundle\Listener\NavigationBuilderListener;
use Tickit\Component\Navigation\Event\NavigationBuildEvent;
use Tickit\Component\Navigation\Model\NavigationItem;

/**
 * NavgationBuilderListener tests
 *
 * @package Tickit\Bundle\TicketBundle\Tests\Listener
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
        $event = new NavigationBuildEvent('main');
        $this->getListener()->onBuild($event);

        $items = $event->getItems();
        $this->assertEquals(2, $items->count());

        /** @var NavigationItem $first */
        $first = $items->current();
        $this->assertEquals('Create Ticket', $first->getText());
        $this->assertEquals('ticket_index', $first->getRouteName());
        $params = $first->getParams();
        $this->assertEquals('plus', $params['icon']);
        $this->assertEquals('add-ticket', $params['class']);
        $this->assertEquals(true, $params['showText']);

        $items->next();
        /** @var NavigationItem $second */
        $second = $items->current();
        $this->assertEquals('Tickets', $second->getText());
        $this->assertEquals('ticket_index', $first->getRouteName());
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

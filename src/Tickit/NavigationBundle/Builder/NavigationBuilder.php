<?php

namespace Tickit\NavigationBundle\Builder;

use Tickit\NavigationBundle\Event\NavigationBuildEvent;
use Tickit\NavigationBundle\TickitNavigationEvents;

/**
 * Main navigation builder.
 *
 * Responsible for building the main navigation structure.
 *
 * @package Tickit\NavigationBundle\Builder
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class NavigationBuilder extends AbstractBuilder implements BuilderInterface
{
    /**
     * Builds the navigation component.
     *
     * @param string $name Navigation name
     *
     * @return \SplPriorityQueue
     */
    public function build($name = 'main')
    {
        $event = new NavigationBuildEvent($name);
        $this->dispatcher->dispatch(TickitNavigationEvents::MAIN_NAVIGATION_BUILD, $event);

        return $event->getItems();
    }
}

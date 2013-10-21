<?php

namespace Tickit\ClientBundle\Listener;

use Symfony\Component\Routing\RouterInterface;
use Tickit\NavigationBundle\Event\NavigationBuildEvent;
use Tickit\NavigationBundle\Model\NavigationItem;

/**
 * Navigation builder listener.
 *
 * Listens for the "tickit.event.main_navigation_build" event and attaches the bundle's
 * relevant navigation items.
 *
 * @package Tickit\ClientBundle\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NavigationBuilderListener
{
    /**
     * Build event for the client navigation
     *
     * @param NavigationBuildEvent $event The navigation build event
     */
    public function onBuild(NavigationBuildEvent $event)
    {
        if ($event->getNavigationName() === 'main') {
            $event->addItem(new NavigationItem('Clients', 'client_index', 2));
        }
    }
}

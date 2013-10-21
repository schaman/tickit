<?php

namespace Tickit\DashboardBundle\Listener;

use Tickit\NavigationBundle\Event\NavigationBuildEvent;
use Tickit\NavigationBundle\Model\NavigationItem;

/**
 * Dashboard navigation builder
 *
 * @package Tickit\DashboardBundle\Listener
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class NavigationBuilderListener
{
    /**
     * Build event for dashboard navigation
     *
     * @param NavigationBuildEvent $event Navigation build event
     *
     * @return void
     */
    public function onBuild(NavigationBuildEvent $event)
    {
        switch ($event->getNavigationName()) {
            case 'main':
                $event->addItem(new NavigationItem('Dashboard', 'dashboard_index', 10));
                break;
        }
    }
}

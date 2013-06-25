<?php

namespace Tickit\ProjectBundle\Listener;

use Tickit\NavigationBundle\Event\NavigationBuildEvent;
use Tickit\NavigationBundle\Model\NavigationItem;

/**
 * Project navigation builder
 *
 * @package Tickit\ProjectBundle\Listener
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class NavigationBuilder
{
    /**
     * Build event for project navigation
     *
     * @param NavigationBuildEvent $event Navigation build event
     *
     * @return void
     */
    public function onBuild(NavigationBuildEvent $event)
    {
        switch ($event->getNavigationName()) {
            case 'main':
                $event->addItem(new NavigationItem('Projects', 'project_index', 8));
                break;
        }
    }
}

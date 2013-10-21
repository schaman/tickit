<?php

namespace Tickit\UserBundle\Listener;

use Tickit\NavigationBundle\Event\NavigationBuildEvent;
use Tickit\NavigationBundle\Model\NavigationItem;

/**
 * User navigation builder listener
 *
 * @package Tickit\UserBundle\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NavigationBuilderListener
{
    /**
     * Build event for user navigation
     *
     * @param NavigationBuildEvent $event Navigation build event
     *
     * @return void
     */
    public function onBuild(NavigationBuildEvent $event)
    {
        switch ($event->getNavigationName()) {
            case 'main':
                $event->addItem(new NavigationItem('Users', 'user_index', 0));
                break;
        }
    }
}

<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tickit\Bundle\IssueBundle\Listener;

use Tickit\Component\Navigation\Builder\NavigationBuilder;
use Tickit\Component\Navigation\Event\NavigationBuildEvent;
use Tickit\Component\Navigation\Model\NavigationItem;

/**
 * Ticket navigation builder listener.
 *
 * @package Tickit\Bundle\IssueBundle\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NavigationBuilderListener
{
    /**
     * Build event for issue navigation
     *
     * @param NavigationBuildEvent $event Navigation build event
     *
     * @return void
     */
    public function onBuild(NavigationBuildEvent $event)
    {
        switch ($event->getNavigationName()) {
            case NavigationBuilder::NAME_MAIN:
                $createIssueItem = new NavigationItem(
                    'Create Issue',
                    '',
                    15,
                    ['icon' => 'plus', 'class' => 'add-ticket', 'showText' => true]
                );
                $issuesItem = new NavigationItem(
                    'Issues',
                    'issue_index',
                    9,
                    ['icon' => 'tags']
                );
                $event->addItem($createIssueItem);
                $event->addItem($issuesItem);
                break;
        }
    }
}

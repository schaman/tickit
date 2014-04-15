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

namespace Tickit\Component\Event\Issue\Dispatcher;

use Tickit\Component\Event\Dispatcher\AbstractEntityEventDispatcher;
use Tickit\Component\Event\Issue\IssueEvents;

/**
 * Event dispatcher for issue events.
 *
 * @package Tickit\Component\Event\Issue\Dispatcher
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class IssueEventDispatcher extends AbstractEntityEventDispatcher
{
    /**
     * Gets an array of event names
     *
     * @return array
     */
    protected function getEventNames()
    {
        return [
            'before_create' => IssueEvents::ISSUE_BEFORE_CREATE,
            'create' => IssueEvents::ISSUE_CREATE,
            'before_update' => IssueEvents::ISSUE_BEFORE_UPDATE,
            'update' => IssueEvents::ISSUE_UPDATE,
            'before_delete' => IssueEvents::ISSUE_BEFORE_DELETE,
            'delete' => IssueEvents::ISSUE_DELETE
        ];
    }
}

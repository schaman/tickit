<?php

/*
 * Tickit, an open source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
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

namespace Tickit\Bundle\ProjectBundle\Event\Dispatcher;

use Tickit\Bundle\ProjectBundle\TickitProjectEvents;
use Tickit\Component\Event\Dispatcher\AbstractEntityEventDispatcher;

/**
 * Event dispatcher for the Project entity
 *
 * This class is responsible for firing events related to the management of the
 * Project entity
 *
 * @package Tickit\Bundle\ProjectBundle\Event\Dispatcher
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @see     Tickit\Bundle\ProjectBundle\Entity\Project
 */
class ProjectEventDispatcher extends AbstractEntityEventDispatcher
{
    /**
     * Gets an array of event names
     *
     * @return array
     */
    protected function getEventNames()
    {
        return [
            'before_create' => TickitProjectEvents::PROJECT_BEFORE_CREATE,
            'create' => TickitProjectEvents::PROJECT_CREATE,
            'before_update' => TickitProjectEvents::PROJECT_BEFORE_UPDATE,
            'update' => TickitProjectEvents::PROJECT_UPDATE,
            'before_delete' => TickitProjectEvents::PROJECT_BEFORE_DELETE,
            'delete' => TickitProjectEvents::PROJECT_DELETE
        ];
    }
}

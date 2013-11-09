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

namespace Tickit\Bundle\UserBundle\Event\Dispatcher;

use Tickit\Bundle\UserBundle\TickitUserEvents;
use Tickit\Component\Event\Dispatcher\AbstractEntityEventDispatcher;

/**
 * Event dispatcher for the User entity
 *
 * This class is responsible for firing events related to the management of the
 * User entity
 *
 * @package Tickit\Bundle\UserBundle\Event\Dispatcher
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @see     Tickit\Bundle\UserBundle\Entity\User
 */
class UserEventDispatcher extends AbstractEntityEventDispatcher
{
    /**
     * Gets an array of event names
     *
     * @return array
     */
    protected function getEventNames()
    {
        return [
            'before_create' => TickitUserEvents::USER_BEFORE_CREATE,
            'create' => TickitUserEvents::USER_CREATE,
            'before_update' => TickitUserEvents::USER_BEFORE_UPDATE,
            'update' => TickitUserEvents::USER_UPDATE,
            'before_delete' => TickitUserEvents::USER_BEFORE_DELETE,
            'delete' => TickitUserEvents::USER_DELETE
        ];
    }
}

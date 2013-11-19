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

namespace Tickit\Bundle\ClientBundle\Event\Dispatcher;

use Tickit\Component\Event\Client\ClientEvents;
use Tickit\Component\Event\Dispatcher\AbstractEntityEventDispatcher;

/**
 * Client event dispatcher.
 *
 * @package Tickit\Bundle\ClientBundle\Event\Dispatcher
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientEventDispatcher extends AbstractEntityEventDispatcher
{
    /**
     * Gets an array of event names
     *
     * @return array
     */
    protected function getEventNames()
    {
        return [
            'before_create' => ClientEvents::CLIENT_BEFORE_CREATE,
            'create' => ClientEvents::CLIENT_CREATE,
            'before_update' => ClientEvents::CLIENT_BEFORE_UPDATE,
            'update' => ClientEvents::CLIENT_UPDATE,
            'before_delete' => ClientEvents::CLIENT_BEFORE_DELETE,
            'delete' => ClientEvents::CLIENT_DELETE
        ];
    }
}

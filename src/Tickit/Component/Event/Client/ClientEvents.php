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

namespace Tickit\Component\Event\Client;

/**
 * Client model events.
 *
 * Contains static event names for Client related model events.
 *
 * @package Tickit\Component\Event\Client
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
final class ClientEvents
{
    /**
     * Constant representing the name of the "before create" event
     *
     * @const string
     */
    const CLIENT_BEFORE_CREATE = 'tickit_client.event.before_create';

    /**
     * Constant representing the name of the "create" event
     *
     * @const string
     */
    const CLIENT_CREATE = 'tickit_client.event.create';

    /**
     * Constant representing the name of the "before update" event
     *
     * @const string
     */
    const CLIENT_BEFORE_UPDATE = 'tickit_client.event.before_update';

    /**
     * Constant representing the name of the "update" event
     *
     * @const string
     */
    const CLIENT_UPDATE = 'tickit_client.event.update';

    /**
     * Constant representing the name of the "before delete" event
     *
     * @const string
     */
    const CLIENT_BEFORE_DELETE = 'tickit_client.event.before_delete';

    /**
     * Constant representing the name of the "delete" event
     *
     * @const string
     */
    const CLIENT_DELETE = 'tickit_client.event.delete';
}

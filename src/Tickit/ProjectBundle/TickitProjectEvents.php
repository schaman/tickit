<?php

/*
 * 
 * Tickit, an source web based bug management tool.
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
 * 
 */

namespace Tickit\ProjectBundle;

/**
 * ProjectBundle events collection.
 *
 * This class contains a collection of constants representing event names for the project bundle
 *
 * @package Tickit\ProjectBundle
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TickitProjectEvents
{
    /**
     * Constant representing the name of the "before create" event
     *
     * @const string
     */
    const PROJECT_BEFORE_CREATE = 'tickit_project.event.before_create';

    /**
     * Constant representing the name of the "create" event
     *
     * @const string
     */
    const PROJECT_CREATE = 'tickit_project.event.create';

    /**
     * Constant representing the name of the "before update" event
     *
     * @const string
     */
    const PROJECT_BEFORE_UPDATE = 'tickit_project.event.before_update';

    /**
     * Constant representing the name of the "update" event
     *
     * @const string
     */
    const PROJECT_UPDATE = 'tickit_project.event.update';

    /**
     * Constant representing the name of the "before delete" event
     *
     * @const string
     */
    const PROJECT_BEFORE_DELETE = 'tickit_project.event.before_delete';

    /**
     * Constant representing the name of the "delete" event
     *
     * @const string
     */
    const PROJECT_DELETE = 'tickit_project.event.delete';
}

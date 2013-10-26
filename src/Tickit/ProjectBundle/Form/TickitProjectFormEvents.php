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

namespace Tickit\ProjectBundle\Form;

/**
 * ProjectBundle form events collection.
 *
 * This class contains a collection of constants representing event names for the project bundle
 * that are specific to forms.
 *
 * @package Tickit\ProjectBundle\Form
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TickitProjectFormEvents
{
    /**
     * Constant representing the name of the event for entity attribute form build
     *
     * @const string
     */
    const ENTITY_ATTRIBUTE_FORM_BUILD = 'tickit_project.form.event.entity_attribute_build';
}

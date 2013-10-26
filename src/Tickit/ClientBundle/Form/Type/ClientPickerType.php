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

namespace Tickit\ClientBundle\Form\Type;

use Tickit\CoreBundle\Form\Type\Picker\AbstractPickerType;

/**
 * Client picker field type.
 *
 * Provides a field for picking a single client.
 *
 * @package Tickit\ClientBundle\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientPickerType extends AbstractPickerType
{
    /**
     * Gets the name of the field that stores the picker IDs
     *
     * @return mixed
     */
    public function getFieldName()
    {
        return 'client';
    }

    /**
     * Gets the restriction type (if any)
     *
     * @return string|null
     */
    public function getRestriction()
    {
        return static::RESTRICTION_SINGLE;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tickit_client_picker';
    }
}

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

namespace Tickit\Bundle\ClientBundle\Form\Type\Picker;

use Tickit\Bundle\PickerBundle\Form\Type\Picker\AbstractPickerType;

/**
 * Client picker field type.
 *
 * Provides a field for picking a single client.
 *
 * @package Tickit\Bundle\ClientBundle\Form\Type\Picker
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientPickerType extends AbstractPickerType
{
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

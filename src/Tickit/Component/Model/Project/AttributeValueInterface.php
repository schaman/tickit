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

namespace Tickit\Component\Model\Project;

/**
 * Interface for entities that must contain an attribute and a value
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
interface AttributeValueInterface
{
    /**
     * Gets the attribute object associated with this record
     *
     * @abstract
     * @return AttributeInterface
     */
    public function getAttribute();

    /**
     * Gets the value associated with this attribute-value pair
     *
     * @abstract
     * @return mixed
     */
    public function getValue();
}

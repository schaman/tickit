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
 * Entity attribute value implementation.
 *
 * Represents a value associated with a EntityAttribute entity
 *
 * @package Tickit\Component\Model\Project
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class EntityAttributeValue extends AbstractAttributeValue
{
    /**
     * The attribute this value is for
     *
     * @var EntityAttribute
     */
    protected $attribute;

    /**
     * The attribute value
     *
     * @var string
     */
    protected $value;

    /**
     * Gets the associated attribute object
     *
     * @return EntityAttribute
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * Sets the value on this attribute value
     *
     * @param mixed $value The new value
     *
     * @return EntityAttributeValue
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Gets the attribute value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Gets the attribute type
     *
     * @return string
     */
    public function getType()
    {
        return AbstractAttribute::TYPE_ENTITY;
    }

    /**
     * Sets the attribute that this value is for
     *
     * @param AbstractAttribute $attribute The new attribute
     *
     * @return EntityAttributeValue
     */
    public function setAttribute(AbstractAttribute $attribute)
    {
        $this->attribute = $attribute;

        return $this;
    }
}

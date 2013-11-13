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

namespace Tickit\Component\Model\Project;

use Doctrine\Common\Collections\Collection;

/**
 * Entity attribute implementation
 *
 * Represents a project attribute that has a predefined list of entities which
 * are used as choices.
 *
 * @package Tickit\Component\Model\Project
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class EntityAttribute extends AbstractAttribute
{
    /**
     * Attribute values that use this entity attribute
     *
     * @var Collection
     */
    protected $values;

    /**
     * The entity class that this attribute is for
     *
     * @var string
     */
    protected $entity;

    /**
     * Gets the attribute type
     *
     * @return string
     */
    public function getType()
    {
        return static::TYPE_ENTITY;
    }

    /**
     * Gets the entity class name that this attribute is for
     *
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Sets the entity class name
     *
     * @param string $entity The new entity class name
     *
     * @return EntityAttribute
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }
}

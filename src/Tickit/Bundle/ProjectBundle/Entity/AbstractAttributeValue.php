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

namespace Tickit\Bundle\ProjectBundle\Entity;

/**
 * The AbstractAttributeValue entity represents a value associated with an attribute for a specific project
 *
 * @package Tickit\Bundle\ProjectBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractAttributeValue implements AttributeValueInterface
{
    /**
     * The unique identifier for this value
     *
     * @var integer
     */
    protected $id;

    /**
     * The project that this attribute value is associated with
     *
     * @var Project
     */
    protected $project;

    /**
     * Gets the attribute type
     *
     * @return string
     */
    abstract public function getType();

    /**
     * Sets the attribute that this value is for
     *
     * @param AbstractAttribute $attribute The new attribute
     *
     * @return mixed
     */
    abstract public function setAttribute(AbstractAttribute $attribute);

    /**
     * Sets the project that this attribute value is associated with
     *
     * @param Project $project The new project
     *
     * @return AbstractAttributeValue
     */
    public function setProject(Project $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Gets the project that this attribute value is for
     *
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Factory method for creating new instances of AttributeValue entities
     *
     * @param string $type The attribute value type to create
     *
     * @throws \InvalidArgumentException If an invalid type was provided
     *
     * @return AbstractAttributeValue
     */
    public static function factory($type)
    {
        if (!in_array($type, AbstractAttribute::getAvailableTypes())) {
            throw new \InvalidArgumentException(
                sprintf('An invalid type was provided (%s)', $type)
            );
        }

        switch ($type) {
            case AbstractAttribute::TYPE_CHOICE:
                $attribute = new ChoiceAttributeValue();
                break;
            case AbstractAttribute::TYPE_ENTITY:
                $attribute = new EntityAttributeValue();
                break;
            default:
                $attribute = new LiteralAttributeValue();
        }

        return $attribute;
    }
}

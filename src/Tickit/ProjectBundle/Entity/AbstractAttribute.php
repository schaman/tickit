<?php

/*
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
 */

namespace Tickit\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * The AbstractAttribute entity represents a specific attribute that is customisable per project
 *
 * @package Tickit\ProjectBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity(repositoryClass="Tickit\ProjectBundle\Entity\Repository\AttributeRepository")
 * @ORM\Table(name="project_attributes")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string", length=7)
 * @ORM\DiscriminatorMap({
 *     "literal" = "LiteralAttribute",
 *     "choice" = "ChoiceAttribute",
 *     "entity" = "EntityAttribute"
 * })
 */
abstract class AbstractAttribute implements AttributeInterface
{
    const TYPE_LITERAL = 'literal';
    const TYPE_CHOICE = 'choice';
    const TYPE_ENTITY = 'entity';

    /**
     * The unique identifier for this attribute
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The name of this attribute
     *
     * @ORM\Column(type="string", length=120)
     */
    protected $name;

    /**
     * The default value for this attribute
     *
     * @ORM\Column(name="default_value", type="string", length=500)
     */
    protected $defaultValue;

    /**
     * Boolean indicating whether this attribute can be left empty
     *
     * @var boolean
     * @ORM\Column(name="allow_blank", type="boolean")
     */
    protected $allowBlank;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->allowBlank = true;
        $this->defaultValue = '';
    }

    /**
     * Gets the attribute ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the attribute ID
     *
     * @param integer $id The new ID
     *
     * @return AbstractAttribute
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Sets the name of this attribute
     *
     * @param string $name
     *
     * @return AbstractAttribute
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the name of this attribute
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the default value for this attribute
     *
     * @param mixed $defaultValue
     *
     * @return AbstractAttribute
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    /**
     * Gets the default value for this attribute
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Sets whether blank values are allowed on this attribute
     *
     * @param boolean $allowBlank
     *
     * @return AbstractAttribute
     */
    public function setAllowBlank($allowBlank)
    {
        $this->allowBlank = $allowBlank;

        return $this;
    }

    /**
     * Returns true if this attribute can have a blank value, false otherwise
     *
     * @return boolean
     */
    public function getAllowBlank()
    {
        return $this->allowBlank;
    }

    /**
     * Gets the attribute type
     *
     * @return string
     */
    abstract public function getType();

    /**
     * Returns array of available attribute types
     *
     * @return array
     */
    public static function getAvailableTypes()
    {
        return array(static::TYPE_CHOICE, static::TYPE_ENTITY, static::TYPE_LITERAL);
    }


    /**
     * Factory method for creating new instances of attribute entities
     *
     * @param string $type The attribute type to create
     *
     * @throws \InvalidArgumentException If an invalid type was provided
     *
     * @return AbstractAttribute
     */
    public static function factory($type)
    {
        if (!in_array($type, static::getAvailableTypes())) {
            throw new \InvalidArgumentException(
                sprintf('An invalid type was provided (%s)', $type)
            );
        }

        switch ($type) {
            case AbstractAttribute::TYPE_CHOICE:
                $attribute = new ChoiceAttribute();
                break;
            case AbstractAttribute::TYPE_ENTITY:
                $attribute = new EntityAttribute();
                break;
            default:
                $attribute = new LiteralAttribute();
        }

        return $attribute;
    }

    /**
     * __toString() method
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}

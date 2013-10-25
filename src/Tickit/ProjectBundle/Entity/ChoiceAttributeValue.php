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

namespace Tickit\ProjectBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

/**
 * Choice attribute value implementation.
 *
 * Represents a value associated with a ChoiceAttribute entity
 *
 * @package Tickit\ProjectBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity
 */
class ChoiceAttributeValue extends AbstractAttributeValue
{
    /**
     * The attribute this value is for
     *
     * @ORM\ManyToOne(targetEntity="ChoiceAttribute", inversedBy="values")
     * @ORM\JoinColumn(name="choice_attribute_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $attribute;

    /**
     * The attribute value.
     *
     * @var Collection
     * @ORM\ManyToMany(targetEntity="ChoiceAttributeChoice", inversedBy="values")
     * @ORM\JoinTable(
     *      name="project_attribute_values_choices",
     *      joinColumns={
     *          @ORM\JoinColumn(name="choice_attribute_value_id", referencedColumnName="id", onDelete="CASCADE")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="choice_id", referencedColumnName="id", onDelete="CASCADE")
     *      }
     * )
     */
    protected $value;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->value = new ArrayCollection();
    }

    /**
     * Gets the associated attribute object
     *
     * @return ChoiceAttribute
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * Sets the selected value(s)
     *
     * @param Collection $value The selected value(s) collection
     *
     * @return ChoiceAttributeValue
     */
    public function setValue(Collection $value = null)
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
        return AbstractAttribute::TYPE_CHOICE;
    }

    /**
     * Sets the attribute that this value is for
     *
     * @param AbstractAttribute $attribute The new attribute
     *
     * @return ChoiceAttributeValue
     */
    public function setAttribute(AbstractAttribute $attribute)
    {
        $this->attribute = $attribute;

        return $this;
    }
}

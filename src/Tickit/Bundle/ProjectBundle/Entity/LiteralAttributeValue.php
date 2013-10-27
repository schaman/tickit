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

use Doctrine\ORM\Mapping as ORM;

/**
 * Literal attribute value implementation.
 *
 * Represents a value associated with a LiteralAttribute entity
 *
 * @package Tickit\Bundle\ProjectBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity
 */
class LiteralAttributeValue extends AbstractAttributeValue
{
    /**
     * The attribute this value is for
     *
     * @var LiteralAttribute
     * @ORM\ManyToOne(targetEntity="LiteralAttribute", inversedBy="values")
     * @ORM\JoinColumn(name="literal_attribute_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $attribute;

    /**
     * The attribute value
     *
     * @ORM\Column(type="string", length=500)
     */
    protected $value;

    /**
     * Gets the associated attribute object
     *
     * @return LiteralAttribute
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * Sets the value of this attribute value
     *
     * @param mixed $value The new value
     *
     * @return LiteralAttributeValue
     */
    public function setValue($value)
    {
        if ($value instanceof \DateTime) {
            switch ($this->attribute->getValidationType()) {
                case LiteralAttribute::VALIDATION_DATE:
                    $value = $value->format('Y-m-d');
                    break;
                case LiteralAttribute::VALIDATION_DATETIME:
                    $value = $value->format('Y-m-d H:i:s');
                    break;
            }
        }

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
        $type = $this->attribute->getValidationType();
        if (in_array($type, array(LiteralAttribute::VALIDATION_DATE, LiteralAttribute::VALIDATION_DATETIME))) {
            return new \DateTime($this->value);
        }

        return $this->value;
    }

    /**
     * Gets the attribute type
     *
     * @return string
     */
    public function getType()
    {
        return AbstractAttribute::TYPE_LITERAL;
    }

    /**
     * Sets the attribute that this value is for
     *
     * @param AbstractAttribute $attribute The new attribute
     *
     * @return LiteralAttributeValue
     */
    public function setAttribute(AbstractAttribute $attribute)
    {
        $this->attribute = $attribute;

        return $this;
    }
}

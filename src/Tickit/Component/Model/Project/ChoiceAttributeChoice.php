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

use Doctrine\Common\Collections\Collection;
use Tickit\Component\Model\IdentifiableInterface;

/**
 * Entity representing a choice available for a ChoiceAttribute
 *
 * @package Tickit\Component\Model\Project
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ChoiceAttributeChoice implements IdentifiableInterface
{
    /**
     * The unique identifier for this choice
     *
     * @var integer
     */
    protected $id;

    /**
     * The attribute that this choice belongs to
     *
     * @var ChoiceAttribute
     */
    protected $attribute;

    /**
     * Any values that have this choice selected
     *
     * @var Collection
     */
    protected $values;

    /**
     * The name of this choice
     *
     * @var string
     */
    protected $name;

    /**
     * Gets the unique identifier for this choice
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the unique identifier
     *
     * @param integer $id The identifier
     *
     * @return ChoiceAttributeChoice
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Sets the attribute that this choice belongs to
     *
     * @param ChoiceAttribute $attribute The attribute
     *
     * @return ChoiceAttributeChoice
     */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * Gets the attribute that this choice belongs to
     *
     * @return ChoiceAttribute
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * Sets the name of this choice
     *
     * @param string $name The new name
     *
     * @return ChoiceAttributeChoice
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the name of this choice
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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

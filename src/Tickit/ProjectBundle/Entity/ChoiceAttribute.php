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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Choice attribute implementation.
 *
 * Represents a project attribute that has a predefined list of choices
 *
 * @package Tickit\ProjectBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity
 */
class ChoiceAttribute extends AbstractAttribute
{
    /**
     * Attribute values that use this choice attribute
     *
     * @var Collection
     * @ORM\OneToMany(targetEntity="ChoiceAttributeValue", mappedBy="attribute")
     */
    protected $values;

    /**
     * Choices that are associated with this choice attribute
     *
     * @var Collection
     * @ORM\OneToMany(targetEntity="ChoiceAttributeChoice", mappedBy="attribute", cascade={"persist"})
     */
    protected $choices;

    /**
     * Boolean indicating whether this choice attribute should be expanded when displayed
     *
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $expanded;

    /**
     * Boolean indicating whether multiple selections are allowed on this attribute
     *
     * @var boolean
     * @ORM\Column(name="allow_multiple", type="boolean")
     */
    protected $allowMultiple;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->expanded = false;
        $this->allowMultiple = false;
        $this->choices = new ArrayCollection();
    }

    /**
     * Gets the attribute type
     *
     * @return string
     */
    public function getType()
    {
        return static::TYPE_CHOICE;
    }

    /**
     * Sets the choices available on this choice attribute
     *
     * @param Collection $choices The new collection of choices
     *
     * @return ChoiceAttribute
     */
    public function setChoices(Collection $choices)
    {
        foreach ($choices as $choice) {
            $choice->setAttribute($this);
        }

        $this->choices = $choices;

        return $this;
    }

    /**
     * Gets available choices
     *
     * @return ArrayCollection
     */
    public function getChoices()
    {
        return $this->choices;
    }

    /**
     * Sets whether this attribute allows multiple selections
     *
     * @param boolean $allowMultiple True if attribute should allow multiple selections
     *
     * @return ChoiceAttribute
     */
    public function setAllowMultiple($allowMultiple)
    {
        $this->allowMultiple = (bool) $allowMultiple;

        return $this;
    }

    /**
     * Returns true if this attribute allows multiple selections
     *
     * @return boolean
     */
    public function getAllowMultiple()
    {
        return $this->allowMultiple;
    }

    /**
     * Sets whether this attribute should display as expanded
     *
     * @param boolean $expanded True or false
     *
     * @return ChoiceAttribute
     */
    public function setExpanded($expanded)
    {
        $this->expanded = $expanded;

        return $this;
    }

    /**
     * Returns true if this attribute should display as expanded
     *
     * @return boolean
     */
    public function getExpanded()
    {
        return $this->expanded;
    }

    /**
     * Returns choices as an indexed array
     *
     * @return array
     */
    public function getChoicesAsArray()
    {
        $return = array();
        $choices = $this->getChoices();
        foreach ($choices as $choice) {
            $return[$choice->getId()] = $choice->getName();
        }

        return $return;
    }
}

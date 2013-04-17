<?php

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
 * @author  James Halsall <jhalsall@rippleffect.com>
 *
 * @ORM\Entity
 */
class ChoiceAttribute extends AbstractAttribute
{
    /**
     * Choices that are associated with this choice attribute
     *
     * @var ArrayCollection
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

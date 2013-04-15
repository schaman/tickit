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
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
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
}

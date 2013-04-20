<?php

namespace Tickit\ProjectBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * Choice attribute value implementation.
 *
 * Represents a value associated with a ChoiceAttribute entity
 *
 * @package Tickit\ProjectBundle\Entity
 * @author  James Halsall <jhalsall@rippleffect.com>
 *
 * @ORM\Entity
 */
class ChoiceAttributeValue extends AbstractAttributeValue
{
    /**
     * The attribute this value is for
     *
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="ChoiceAttribute")
     * @ORM\JoinColumn(name="choice_attribute_id", referencedColumnName="id")
     */
    protected $attribute;

    /**
     * The attribute value
     *
     * @var Collection
     * @ORM\ManyToMany(targetEntity="ChoiceAttributeChoice", inversedBy="values")
     * @ORM\JoinTable(
     *      name="choice_attribute_value_choices",
     *      joinColumns={
     *          @ORM\JoinColumn(name="attribute_value_id", referencedColumnName="choice_attribute_id"),
     *          @ORM\JoinColumn(name="project_id", referencedColumnName="project_id")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="choice_id", referencedColumnName="id")
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
     * @return mixed
     */
    public function setAttribute(AbstractAttribute $attribute)
    {
        $this->attribute = $attribute;
    }
}

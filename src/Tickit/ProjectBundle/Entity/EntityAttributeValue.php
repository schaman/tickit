<?php

namespace Tickit\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity attribute value implementation.
 *
 * Represents a value associated with a EntityAttribute entity
 *
 * @package Tickit\ProjectBundle\Entity
 * @author  James Halsall <jhalsall@rippleffect.com>
 *
 * @ORM\Entity
 */
class EntityAttributeValue extends AbstractAttributeValue
{
    /**
     * The attribute this value is for
     *
     * @ORM\OneToOne(targetEntity="EntityAttribute")
     * @ORM\JoinColumn(name="entity_attribute_id", referencedColumnName="id")
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
     * @return EntityAttribute
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * Sets the value on this attribute value
     *
     * @param mixed $value The new value
     *
     * @return EntityAttributeValue
     */
    public function setValue($value)
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
        return AbstractAttribute::TYPE_ENTITY;
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

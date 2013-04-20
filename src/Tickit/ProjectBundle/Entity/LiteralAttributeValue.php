<?php

namespace Tickit\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Literal attribute value implementation.
 *
 * Represents a value associated with a LiteralAttribute entity
 *
 * @package Tickit\ProjectBundle\Entity
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
     * @ORM\OneToOne(targetEntity="LiteralAttribute")
     * @ORM\JoinColumn(name="literal_attribute_id", referencedColumnName="id")
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

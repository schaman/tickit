<?php

namespace Tickit\ProjectBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Literal Attribute implementation
 *
 * Represents a project attribute that expects a literal (string, number etc.)
 * value and offers validation options.
 *
 * @package Tickit\ProjectBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity
 */
class LiteralAttribute extends AbstractAttribute
{
    const VALIDATION_EMAIL = 'email';
    const VALIDATION_NUMBER = 'number';
    const VALIDATION_URL = 'url';
    const VALIDATION_IP = 'ip';
    const VALIDATION_DATE = 'date';
    const VALIDATION_DATETIME = 'datetime';
    const VALIDATION_FILE = 'file';

    /**
     * The validation type on this attribute
     *
     * @var string
     * @ORM\Column(name="validation_type", type="string", length=15)
     */
    protected $validationType;

    /**
     * Attribute values that use this attribute.
     *
     * @var Collection
     * @ORM\OneToMany(targetEntity="LiteralAttributeValue", mappedBy="attribute")
     */
    protected $values;

    /**
     * Gets the attribute type
     *
     * @return string
     */
    public function getType()
    {
        return static::TYPE_LITERAL;
    }

    /**
     * Gets the validation type for this attribute
     *
     * @return string
     */
    public function getValidationType()
    {
        return $this->validationType;
    }

    /**
     * Sets the validation type for this attribute
     *
     * @param string $validationType The new validation type for this attribute
     *
     * @throws \InvalidArgumentException If an invalid validation type is provided
     *
     * @return LiteralAttribute
     */
    public function setValidationType($validationType)
    {
        if (!in_array($validationType, array_flip(static::getValidationTypes()))) {
            throw new \InvalidArgumentException(
                sprintf('An invalid validation type(%s) was provided', $validationType)
            );
        }

        $this->validationType = $validationType;

        return $this;
    }

    /**
     * Gets an array of all the available validation types
     *
     * @return array
     */
    public static function getValidationTypes()
    {
        $types = array(
            static::VALIDATION_EMAIL => 'Email',
            static::VALIDATION_NUMBER => 'Number',
            static::VALIDATION_URL => 'Web Address',
            static::VALIDATION_IP => 'IP Address',
            static::VALIDATION_DATE => 'Date',
            static::VALIDATION_DATETIME => 'Date and Time',
            static::VALIDATION_FILE => 'File'
        );

        return $types;
    }
}

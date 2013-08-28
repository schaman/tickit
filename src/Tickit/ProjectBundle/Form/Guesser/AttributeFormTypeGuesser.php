<?php

namespace Tickit\ProjectBundle\Form\Guesser;

use Symfony\Component\Form\AbstractType;
use Tickit\ProjectBundle\Entity\AbstractAttribute;
use Tickit\ProjectBundle\Form\Type\ChoiceAttributeFormType;
use Tickit\ProjectBundle\Form\Type\EntityAttributeFormType;
use Tickit\ProjectBundle\Form\Type\LiteralAttributeFormType;

/**
 * Attribute form type guesser.
 *
 * @package Tickit\ProjectBundle\Form\Guesser
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AttributeFormTypeGuesser
{
    /**
     * An instance of the entity attribute form type
     *
     * @var EntityAttributeFormType
     */
    protected $entityAttributeFormType;

    /**
     * Constructor.
     *
     * @param EntityAttributeFormType $entityAttributeFormType An entity attribute form type instance
     */
    public function __construct(EntityAttributeFormType $entityAttributeFormType)
    {
        $this->entityAttributeFormType = $entityAttributeFormType;
    }

    /**
     * Guesses a form type based off an attribute type.
     *
     * @param string $attributeType The attribute type to guess for
     *
     * @throws \InvalidArgumentException If an invalid attribute type is specified
     *
     * @return AbstractType
     */
    public function guessByAttributeType($attributeType)
    {
        if (!in_array($attributeType, AbstractAttribute::getAvailableTypes())) {
            throw new \InvalidArgumentException(
                sprintf('Attribute type (%s) is not recognised', $attributeType)
            );
        }

        switch ($attributeType) {
            case AbstractAttribute::TYPE_CHOICE:
                $formType = new ChoiceAttributeFormType();
                break;
            case AbstractAttribute::TYPE_ENTITY:
                $formType = $this->entityAttributeFormType;
                break;
            default:
                $formType = new LiteralAttributeFormType();
        }

        return $formType;
    }
}

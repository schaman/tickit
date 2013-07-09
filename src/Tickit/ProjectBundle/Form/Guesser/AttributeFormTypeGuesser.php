<?php

namespace Tickit\ProjectBundle\Form\Guesser;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Tickit\ProjectBundle\Entity\AbstractAttribute;
use Tickit\ProjectBundle\Form\Type\ChoiceAttributeFormType;
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
     * The container
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container The service container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
                $formType = $this->container->get('tickit_project.form.entity_attribute');
                break;
            default:
                $formType = new LiteralAttributeFormType();
        }

        return $formType;
    }
}

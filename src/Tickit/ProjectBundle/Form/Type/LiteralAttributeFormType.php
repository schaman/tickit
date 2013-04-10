<?php

namespace Tickit\ProjectBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Tickit\ProjectBundle\Entity\AbstractAttribute;
use Tickit\ProjectBundle\Entity\LiteralAttribute;

/**
 * Literal attribute form type.
 *
 * Provides functionality for adding/editing a literal attribute.
 *
 * @package Tickit\ProjectBundle\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class LiteralAttributeFormType extends AbstractAttributeFormType
{
    /**
     * Builds the form
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array $options                Form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $validationTypes = LiteralAttribute::getValidationTypes();
        $validationTypes[''] = 'None';

        $builder->add('type', 'hidden', array('data' => AbstractAttribute::TYPE_LITERAL))
                ->add('validation_type', 'choice', array('choices' => $validationTypes));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tickit_project_attribute_literal';
    }
}
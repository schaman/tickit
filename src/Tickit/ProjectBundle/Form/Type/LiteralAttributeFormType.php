<?php

namespace Tickit\ProjectBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

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

        $builder->add('validation', 'choice', array(
            'choices' => array(
                '' => 'None',
                'email' => 'Email Address',
                'number' => 'Number',
                'url' => 'Web Address',
                'ip' => 'IP Address',
                'date' => 'Date',
                'datetime' => 'Date and Time',
                'file' => 'File'
            ),
            'mapped' => false
        ));
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
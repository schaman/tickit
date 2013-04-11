<?php

namespace Tickit\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Abstract implementation of an attribute form.
 *
 * Provides common fields for all attribute forms.
 *
 * @package Tickit\ProjectBundle\Form\Type
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
abstract class AbstractAttributeFormType extends AbstractType
{
    /**
     * Builds the form
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array $options                Form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
                ->add('default_value')
                ->add('allow_blank', 'choice', array(
                    'choices' => array(
                        0 => 'No',
                        1 => 'Yes'
                    )
                ));
    }
}
